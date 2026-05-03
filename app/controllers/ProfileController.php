<?php
/**
 * ProfileController.php
 * Allows any logged-in user to view and update their own profile.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/Faculty.php';
require_once ROOT_PATH . '/app/models/Department.php';

class ProfileController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * GET /profile
     * Show the current user's profile page.
     */
    public function show(): void
    {
        $tenantId = Auth::tenantId();
        $userId   = Auth::id();

        // Fetch full user row with joins
        $user = $this->userModel->raw("
            SELECT u.*, d.name as department_name, f.name as faculty_name, un.name as unit_name
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.id
            LEFT JOIN faculties f ON u.faculty_id = f.id
            LEFT JOIN units un ON u.unit_id = un.id
            WHERE u.id = :id AND u.tenant_id = :tid
        ", [':id' => $userId, ':tid' => $tenantId])[0] ?? [];

        $faculties   = (new Faculty())->all($tenantId);
        $departments = (new Department())->all($tenantId);

        $this->view('profile.show', [
            'user'        => $user,
            'faculties'   => $faculties,
            'departments' => $departments
        ]);
    }

    /**
     * POST /profile
     * Handle profile info update OR password change (based on action field).
     */
    public function update(): void
    {
        if (!$this->isPost()) $this->redirect('/profile');

        $tenantId = Auth::tenantId();
        $userId   = Auth::id();
        $action   = $this->post('action', 'profile');

        if ($action === 'images') {
            // ── Update Profile & Banner Images ──
            $data = [];
            $uploadDir = ROOT_PATH . '/public/uploads/';

            // Profile Image
            if (!empty($_FILES['profile_image']['name'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    $filename = 'profile_' . $userId . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . 'profiles/' . $filename)) {
                        $data['profile_image'] = '/uploads/profiles/' . $filename;
                    }
                }
            }

            // Banner Image
            if (!empty($_FILES['banner_image']['name'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                $ext = strtolower(pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    $filename = 'banner_' . $userId . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $uploadDir . 'banners/' . $filename)) {
                        $data['banner_image'] = '/uploads/banners/' . $filename;
                    }
                }
            }

            if (!empty($data)) {
                $this->userModel->update($userId, $data, $tenantId);
                // Update session if profile image changed
                if (isset($data['profile_image'])) {
                    $_SESSION['auth']['profile_image'] = $data['profile_image'];
                }
                
                // Send notification
                (new Notification())->send($userId, $tenantId, 'Pictures Updated', 'Your profile/banner images have been successfully changed.', 'success', '/profile');

                $this->flash('success', 'Images updated successfully.');
            } else {
                $this->flash('info', 'No valid images selected.');
            }
            $this->redirect('/profile');
        } elseif ($action === 'password') {
            // ── Change Password ──
            $current = $this->post('current_password');
            $new     = $this->post('new_password');
            $confirm = $this->post('confirm_password');

            if (!$current || !$new || !$confirm) {
                $this->flash('error', 'All password fields are required.');
                $this->redirect('/profile#password');
            }

            // Verify current password
            $userRow = $this->userModel->find($userId, $tenantId);
            if (!password_verify($current, $userRow['password'])) {
                $this->flash('error', 'Your current password is incorrect.');
                $this->redirect('/profile#password');
            }

            if ($new !== $confirm) {
                $this->flash('error', 'New passwords do not match.');
                $this->redirect('/profile#password');
            }

            if (strlen($new) < 8) {
                $this->flash('error', 'New password must be at least 8 characters.');
                $this->redirect('/profile#password');
            }

            $this->userModel->update($userId, [
                'password' => password_hash($new, PASSWORD_BCRYPT)
            ], $tenantId);

            $this->flash('success', 'Password changed successfully.');
            $this->redirect('/profile#password');
        } else {
            // ── Update Profile Info ──
            $name  = $this->post('name');
            $email = $this->post('email');
            $phone = $this->post('phone');

            if (!$name || !$email) {
                $this->flash('error', 'Name and Email are required.');
                $this->redirect('/profile');
            }

            $data = [
                'name'          => $name,
                'email'         => $email,
                'phone'         => $phone,
                'gender'        => $this->post('gender') ?: null,
                'date_of_birth' => $this->post('date_of_birth') ?: null,
                'address'       => $this->post('address'),
            ];

            // Students & academic staff can update their placements
            $role = Auth::role();
            if (in_array($role, ['student', 'lecturer', 'hod', 'dean'])) {
                $data['faculty_id']    = $this->post('faculty_id') ?: null;
                $data['department_id'] = $this->post('department_id') ?: null;
            }

            $this->userModel->update($userId, $data, $tenantId);

            // Update the session data so the name in the header refreshes
            $_SESSION['auth']['name'] = $name;

            // Send notification
            (new Notification())->send($userId, $tenantId, 'Profile Updated', 'Your personal information has been successfully updated.', 'success', '/profile');

            $this->flash('success', 'Profile updated successfully.');
            $this->redirect('/profile');
        }
    }
}
