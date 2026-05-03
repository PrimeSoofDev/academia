<?php
/**
 * DepartmentController.php
 * Handles CRUD and view logic for Departments
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/Department.php';
require_once ROOT_PATH . '/app/models/Faculty.php';
require_once ROOT_PATH . '/app/models/User.php';

class DepartmentController extends Controller
{
    private Department $deptModel;
    private Faculty $facultyModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->deptModel = new Department();
        $this->facultyModel = new Faculty();
        $this->userModel = new User();
    }

    /**
     * GET /departments
     * List all departments
     */
    public function index(): void
    {
        $tenantId = Auth::tenantId();
        $departments = $this->deptModel->allWithDetails($tenantId);

        $this->view('departments.index', [
            'departments' => $departments
        ]);
    }

    /**
     * GET /departments/create
     * Show create form
     */
    public function create(): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean']);
        
        $tenantId = Auth::tenantId();
        $faculties = $this->facultyModel->all($tenantId);
        $potentialHods = $this->userModel->getByRole('lecturer', $tenantId);

        // Pre-select faculty if passed via query string
        $selectedFacultyId = (int) $this->get('faculty_id');

        $this->view('departments.create', [
            'faculties' => $faculties,
            'potentialHods' => $potentialHods,
            'selectedFacultyId' => $selectedFacultyId
        ]);
    }

    /**
     * POST /departments/create
     * Store new department
     */
    public function store(): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean']);
        if (!$this->isPost()) $this->redirect('/departments');

        $name = $this->post('name');
        $code = $this->post('code');
        $facultyId = (int) $this->post('faculty_id');
        $hodId = $this->post('hod_id') ? (int) $this->post('hod_id') : null;

        if ($name && $code && $facultyId) {
            $this->deptModel->create([
                'tenant_id'   => Auth::tenantId(),
                'faculty_id'  => $facultyId,
                'name'        => $name,
                'code'        => strtoupper($code),
                'hod_id'      => $hodId,
                'description' => $this->post('description')
            ]);
            $this->flash('success', "Department '{$name}' created successfully.");
            $this->redirect("/faculties/{$facultyId}");
        } else {
            $this->flash('error', 'Faculty, Department Name, and Code are required.');
            $this->redirect('/departments/create');
        }
    }

    /**
     * GET /departments/{id}
     * Show a single department and its courses
     */
    public function show(int $id): void
    {
        $tenantId = Auth::tenantId();
        $department = $this->deptModel->find($id, $tenantId);
        
        if (!$department) {
            $this->flash('error', 'Department not found.');
            $this->redirect('/departments');
        }

        $faculty = $this->facultyModel->find($department['faculty_id'], $tenantId);
        $hod = $department['hod_id'] ? $this->userModel->find($department['hod_id'], $tenantId) : null;
        $courses = $this->deptModel->getCourses($id, $tenantId);

        $this->view('departments.show', [
            'department' => $department,
            'faculty'    => $faculty,
            'hod'        => $hod,
            'courses'    => $courses
        ]);
    }

    /**
     * GET /departments/{id}/edit
     * Show edit form
     */
    public function edit(int $id): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean']);
        
        $tenantId = Auth::tenantId();
        $department = $this->deptModel->find($id, $tenantId);
        
        if (!$department) {
            $this->redirect('/departments');
        }

        $faculties = $this->facultyModel->all($tenantId);
        $potentialHods = $this->userModel->getByRole('lecturer', $tenantId);

        $this->view('departments.edit', [
            'department'    => $department,
            'faculties'     => $faculties,
            'potentialHods' => $potentialHods
        ]);
    }

    /**
     * POST /departments/{id}/edit
     * Update department
     */
    public function update(int $id): void
    {
        Auth::authorize(['superadmin', 'vc', 'dean']);
        if (!$this->isPost()) $this->redirect("/departments/{$id}/edit");

        $name = $this->post('name');
        $code = $this->post('code');
        $facultyId = (int) $this->post('faculty_id');
        $hodId = $this->post('hod_id') ? (int) $this->post('hod_id') : null;

        if ($name && $code && $facultyId) {
            $this->deptModel->update($id, [
                'faculty_id'  => $facultyId,
                'name'        => $name,
                'code'        => strtoupper($code),
                'hod_id'      => $hodId,
                'description' => $this->post('description')
            ], Auth::tenantId());
            
            $this->flash('success', 'Department updated successfully.');
            $this->redirect("/departments/{$id}");
        }

        $this->flash('error', 'Faculty, Name, and Code are required.');
        $this->redirect("/departments/{$id}/edit");
    }

    /**
     * POST /departments/{id}/delete
     */
    public function destroy(int $id): void
    {
        Auth::authorize(['superadmin', 'vc']);
        
        $this->deptModel->delete($id, Auth::tenantId());
        $this->flash('success', 'Department deleted successfully.');
        $this->redirect('/departments');
    }
}
