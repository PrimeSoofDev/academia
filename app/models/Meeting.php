<?php
/**
 * Meeting.php
 * Model for calendar meetings and bookings.
 */

require_once ROOT_PATH . '/app/core/Model.php';

class Meeting extends Model
{
    protected string $table = 'meetings';

    /**
     * Get meetings where the user is either host or guest.
     */
    public function getForUser(int $userId, int $tenantId): array
    {
        return $this->raw("
            SELECT m.*, 
                   u1.name as host_name, u1.profile_image as host_image,
                   u2.name as guest_name, u2.profile_image as guest_image
            FROM {$this->table} m
            JOIN users u1 ON m.host_id = u1.id
            LEFT JOIN users u2 ON m.guest_id = u2.id
            WHERE (m.host_id = :uid1 OR m.guest_id = :uid2) 
              AND m.tenant_id = :tid
            ORDER BY m.start_time ASC
        ", [':uid1' => $userId, ':uid2' => $userId, ':tid' => $tenantId]);
    }
}
