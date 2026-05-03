<?php
/**
 * ProfileController.php
 * Allows any logged-in user to view and update their own profile.
 */

require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/Faculty.php';
require_once ROOT_PATH . '/app/models/Department.php';
require_once ROOT_PATH . '/app/models/Notification.php';

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
            // ── Update Profile, Banner & Signature ──
            $data = [];
            $uploadDir = ROOT_PATH . '/public/uploads/';

            // Profile Image
            if (!empty($_FILES['profile_image']['name'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $filename = 'profile_' . $userId . '_' . time() . '.' . $ext;
                    $dir = $uploadDir . 'profiles/';
                    if (!is_dir($dir)) mkdir($dir, 0777, true);
                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dir . $filename)) {
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
                    $dir = $uploadDir . 'banners/';
                    if (!is_dir($dir)) mkdir($dir, 0777, true);
                    if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $dir . $filename)) {
                        $data['banner_image'] = '/uploads/banners/' . $filename;
                    }
                }
            }

            // Signature Upload
            if (!empty($_FILES['signature']['name'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $filename = 'sig_' . $userId . '_' . time() . '.' . $ext;
                    $sigDir = $uploadDir . 'signatures/';
                    if (!is_dir($sigDir)) mkdir($sigDir, 0777, true);
                    if (move_uploaded_file($_FILES['signature']['tmp_name'], $sigDir . $filename)) {
                        $data['signature_path'] = '/uploads/signatures/' . $filename;
                    }
                }
            }

            // Digital Stamp Upload
            if (!empty($_FILES['stamp']['name'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['stamp']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $filename = 'stamp_' . $userId . '_' . time() . '.' . $ext;
                    $stampDir = $uploadDir . 'signatures/'; // Keep in signatures for now
                    if (!is_dir($stampDir)) mkdir($stampDir, 0777, true);
                    if (move_uploaded_file($_FILES['stamp']['tmp_name'], $stampDir . $filename)) {
                        $data['stamp_path'] = '/uploads/signatures/' . $filename;
                    }
                }
            }

            if (!empty($data)) {
                $this->userModel->update($userId, $data, $tenantId);
                if (isset($data['profile_image'])) {
                    $_SESSION['auth']['profile_image'] = $data['profile_image'];
                }
                (new Notification())->send($userId, $tenantId, 'Profile Assets Updated', 'Your pictures or signature have been successfully changed.', 'success', '/profile');
                $this->flash('success', 'Profile assets updated successfully.');
            } else {
                $this->flash('info', 'No valid files selected.');
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

            $this->userModel->update($userId, [
                'name'  => $name,
                'email' => $email,
                'phone' => $phone,
                'gender'=> $this->post('gender'),
                'department_id' => $this->post('department_id') ?: null,
                'faculty_id'    => $this->post('faculty_id')    ?: null,
            ], $tenantId);

            $_SESSION['auth']['name'] = $name;
            $_SESSION['auth']['email'] = $email;

            $this->flash('success', 'Profile updated successfully.');
            $this->redirect('/profile');
        }
    }
}
