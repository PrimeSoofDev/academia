<?php
/**
 * User.php — User Model
 *
 * Represents any user in the system: VC, Dean, HOD,
 * Lecturer, Student, or administrative Staff.
 * All queries are automatically tenant-scoped.
 */

require_once ROOT_PATH . '/app/core/Model.php';

class User extends Model
{
    protected string $table = 'users';

    // ─────────────────────────────────────────────
    // AUTHENTICATION
    // ─────────────────────────────────────────────

    /**
     * Find a user by email (for login).
     * Note: email + tenant_id must be unique.
     */
    public function findByEmail(string $email, int $tenantId): array|false
    {
        return $this->db->fetchOne(
            'SELECT * FROM users WHERE email = :email AND tenant_id = :tenant_id AND status = "active" LIMIT 1',
            [':email' => $email, ':tenant_id' => $tenantId]
        );
    }

    /**
     * Find a tenant by their subdomain/slug for multi-tenant login.
     * Used before we know the tenant_id.
     */
    public function findTenantBySlug(string $slug): array|false
    {
        return $this->db->fetchOne(
            'SELECT * FROM tenants WHERE slug = :slug AND status = "active" LIMIT 1',
            [':slug' => $slug]
        );
    }

    // ─────────────────────────────────────────────
    // ROLE-BASED QUERIES
    // ─────────────────────────────────────────────

    /**
     * Get all users with a specific role, scoped to a tenant.
     */
    public function getByRole(string $role, int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT u.*, d.name as department_name, f.name as faculty_name
             FROM users u
             LEFT JOIN departments d ON u.department_id = d.id
             LEFT JOIN faculties f ON u.faculty_id = f.id
             WHERE u.role = :role AND u.tenant_id = :tenant_id AND u.status = "active"
             ORDER BY u.name ASC',
            [':role' => $role, ':tenant_id' => $tenantId]
        );
    }

    /**
     * Get all students in a specific department.
     */
    public function getStudentsByDepartment(int $departmentId, int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT * FROM users
             WHERE department_id = :dept_id AND tenant_id = :tenant_id AND role = "student" AND status = "active"
             ORDER BY name ASC',
            [':dept_id' => $departmentId, ':tenant_id' => $tenantId]
        );
    }

    /**
     * Get all users belonging to a specific administrative unit.
     */
    public function getByUnit(int $unitId, int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT * FROM users WHERE unit_id = :unit_id AND tenant_id = :tenant_id ORDER BY name ASC',
            [':unit_id' => $unitId, ':tenant_id' => $tenantId]
        );
    }

    // ─────────────────────────────────────────────
    // USER MANAGEMENT
    // ─────────────────────────────────────────────

    /**
     * Create a new user with a hashed password.
     */
    public function register(array $data): int
    {
        $data['password'] = Auth::hashPassword($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }

    /**
     * Update user profile fields.
     */
    public function updateProfile(int $userId, array $data, int $tenantId): bool
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($userId, $data, $tenantId);
    }

    /**
     * Change a user's password.
     */
    public function changePassword(int $userId, string $newPassword, int $tenantId): bool
    {
        return $this->update($userId, [
            'password'   => Auth::hashPassword($newPassword),
            'updated_at' => date('Y-m-d H:i:s'),
        ], $tenantId);
    }

    /**
     * Get count of users by role for dashboard stats.
     */
    public function countByRole(int $tenantId): array
    {
        $results = $this->db->fetchAll(
            'SELECT role, COUNT(*) as total FROM users WHERE tenant_id = :tenant_id AND status = "active" GROUP BY role',
            [':tenant_id' => $tenantId]
        );
        // Index by role for easy access
        $counts = [];
        foreach ($results as $row) {
            $counts[$row['role']] = (int)$row['total'];
        }
        return $counts;
    }

    /**
     * Search users by name or email.
     */
    public function search(string $term, int $tenantId): array
    {
        return $this->db->fetchAll(
            'SELECT * FROM users
             WHERE tenant_id = :tenant_id
             AND (name LIKE :term OR email LIKE :term)
             ORDER BY name ASC LIMIT 50',
            [':tenant_id' => $tenantId, ':term' => '%' . $term . '%']
        );
    }
}
