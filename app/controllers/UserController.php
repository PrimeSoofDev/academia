<?php
/**
 * UserController.php
 * Handles user management (Admins, Staff, Lecturers, Students).
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/Faculty.php';
require_once ROOT_PATH . '/app/models/Department.php';

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * GET /users
     * List all users
     */
    public function index(): void
    {
        $tenantId = Auth::tenantId();
        $role     = Auth::role();
        $userId   = Auth::id();
        
        // Get current user details to know their department/faculty
        $currentUser = $this->userModel->find($userId, $tenantId);
        
        $roleFilter = $this->get('role');
        
        $sql = "SELECT u.*, d.name as department_name, f.name as faculty_name, un.name as unit_name
                FROM users u
                LEFT JOIN departments d ON u.department_id = d.id
                LEFT JOIN faculties f ON u.faculty_id = f.id
                LEFT JOIN units un ON u.unit_id = un.id
                WHERE u.tenant_id = :tenant_id";
        
        $bindings = [':tenant_id' => $tenantId];

        // ── ROLE-BASED FILTERING ──
        if ($role === 'hod') {
            // HOD sees only users in their department
            $sql .= " AND u.department_id = :dept_id";
            $bindings[':dept_id'] = $currentUser['department_id'];
        } elseif ($role === 'dean') {
            // Dean sees only users in their faculty
            $sql .= " AND u.faculty_id = :faculty_id";
            $bindings[':faculty_id'] = $currentUser['faculty_id'];
        }

        if ($roleFilter && in_array($roleFilter, ['superadmin', 'vc', 'dean', 'hod', 'lecturer', 'staff', 'student'])) {
            $sql .= " AND u.role = :role";
            $bindings[':role'] = $roleFilter;
        }

        $sql .= " ORDER BY u.created_at DESC";

        $users = $this->userModel->raw($sql, $bindings);
        $roleCounts = $this->userModel->countByRole($tenantId);

        $this->view('users.index', [
            'users' => $users,
            'roleCounts' => $roleCounts,
            'currentRole' => $roleFilter
        ]);
    }

    /**
     * GET /users/create
     * Show user creation form
     */
    public function create(): void
    {
        Auth::authorize(['superadmin', 'vc']); // Dean/HOD cannot create
        
        $tenantId = Auth::tenantId();
        $faculties = (new Faculty())->all($tenantId);
        $departments = (new Department())->all($tenantId);

        $this->view('users.create', [
            'faculties' => $faculties,
            'departments' => $departments
        ]);
    }

    /**
     * POST /users/create
     * Store new user
     */
    public function store(): void
    {
        Auth::authorize(['superadmin', 'vc']); // Dean/HOD cannot create
        
        if (!$this->isPost()) $this->redirect('/users');

        $name = $this->post('name');
        $email = $this->post('email');
        $role = $this->post('role', 'student');
        $password = $this->post('password') ?: 'password123'; // Default password

        // Check if email exists
        if ($this->userModel->findByEmail($email, Auth::tenantId())) {
            $this->flash('error', 'A user with this email already exists.');
            $this->redirect('/users/create');
        }

        if ($name && $email) {
            $data = [
                'tenant_id'     => Auth::tenantId(),
                'name'          => $name,
                'email'         => $email,
                'password'      => Auth::hashPassword($password),
                'role'          => $role,
                'status'        => 'active',
                'phone'         => $this->post('phone'),
                'gender'        => $this->post('gender') ?: null,
            ];

            // Role specific logic
            if ($role === 'student') {
                $data['matric_number'] = $this->post('identifier');
                $data['department_id'] = $this->post('department_id') ?: null;
                $data['faculty_id']    = $this->post('faculty_id') ?: null;
            } else {
                $data['staff_id'] = $this->post('identifier');
                if (in_array($role, ['lecturer', 'hod', 'dean'])) {
                    $data['department_id'] = $this->post('department_id') ?: null;
                    $data['faculty_id']    = $this->post('faculty_id') ?: null;
                }
            }

            $userId = $this->userModel->create($data);
            $this->flash('success', "User '{$name}' created successfully.");
            $this->redirect('/users');
        } else {
            $this->flash('error', 'Name and Email are required.');
            $this->redirect('/users/create');
        }
    }

    /**
     * GET /users/{id}
     * Show single user profile
     */
    public function show(int $id): void
    {
        $tenantId = Auth::tenantId();
        
        $user = $this->userModel->raw("
            SELECT u.*, d.name as department_name, f.name as faculty_name, un.name as unit_name
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.id
            LEFT JOIN faculties f ON u.faculty_id = f.id
            LEFT JOIN units un ON u.unit_id = un.id
            WHERE u.id = :id AND u.tenant_id = :tenant_id
        ", [':id' => $id, ':tenant_id' => $tenantId])[0] ?? false;

        if (!$user) {
            $this->flash('error', 'User not found.');
            $this->redirect('/users');
        }

        $this->view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * GET /users/{id}/edit
     */
    public function edit(int $id): void
    {
        $tenantId = Auth::tenantId();
        $user = $this->userModel->find($id, $tenantId);
        
        if (!$user) $this->redirect('/users');

        $faculties = (new Faculty())->all($tenantId);
        $departments = (new Department())->all($tenantId);

        $this->view('users.edit', [
            'user' => $user,
            'faculties' => $faculties,
            'departments' => $departments
        ]);
    }

    /**
     * POST /users/{id}/edit
     */
    public function update(int $id): void
    {
        if (!$this->isPost()) $this->redirect("/users/{$id}/edit");

        $name = $this->post('name');
        $email = $this->post('email');
        $role = $this->post('role');

        if ($name && $email && $role) {
            $data = [
                'name'          => $name,
                'email'         => $email,
                'role'          => $role,
                'phone'         => $this->post('phone'),
                'gender'        => $this->post('gender') ?: null,
            ];

            if ($role === 'student') {
                $data['matric_number'] = $this->post('identifier');
            } else {
                $data['staff_id'] = $this->post('identifier');
            }
            
            $data['department_id'] = $this->post('department_id') ?: null;
            $data['faculty_id']    = $this->post('faculty_id') ?: null;

            // Optional password update
            if ($this->post('password')) {
                $data['password'] = Auth::hashPassword($this->post('password'));
            }

            $this->userModel->update($id, $data, Auth::tenantId());
            $this->flash('success', 'User updated successfully.');
            $this->redirect("/users/{$id}");
        }

        $this->flash('error', 'Name, Email, and Role are required.');
        $this->redirect("/users/{$id}/edit");
    }

    /**
     * POST /users/{id}/status
     * Toggle active/suspended/inactive status
     */
    public function updateStatus(int $id): void
    {
        if (!$this->isPost()) $this->redirect('/users');
        
        // Prevent disabling oneself
        if ($id === Auth::id()) {
            $this->flash('error', 'You cannot change your own status.');
            $this->redirect("/users/{$id}");
        }

        $status = $this->post('status');
        if (in_array($status, ['active', 'inactive', 'suspended'])) {
            $this->userModel->update($id, ['status' => $status], Auth::tenantId());
            $this->flash('success', "User status changed to {$status}.");
        }

        $this->redirect("/users/{$id}");
    }
}
