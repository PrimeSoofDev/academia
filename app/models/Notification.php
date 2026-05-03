<?php
/**
 * Notification.php
 * Model for user notifications.
 */

require_once ROOT_PATH . '/app/core/Model.php';

class Notification extends Model
{
    protected string $table = 'notifications';

    /**
     * Get unread notifications for a specific user.
     */
    public function getUnread(int $userId, int $tenantId, int $limit = 5): array
    {
        return $this->raw("
            SELECT * FROM {$this->table} 
            WHERE user_id = :uid AND tenant_id = :tid AND is_read = 0 
            ORDER BY created_at DESC 
            LIMIT :limit
        ", [':uid' => $userId, ':tid' => $tenantId, ':limit' => $limit]);
    }

    /**
     * Count unread notifications.
     */
    public function countUnread(int $userId, int $tenantId): int
    {
        $res = $this->raw("
            SELECT COUNT(*) as total FROM {$this->table} 
            WHERE user_id = :uid AND tenant_id = :tid AND is_read = 0
        ", [':uid' => $userId, ':tid' => $tenantId]);
        
        return (int)($res[0]['total'] ?? 0);
    }

    /**
     * Create a new notification.
     */
    public function send(int $userId, int $tenantId, string $title, string $message, string $type = 'info', string $link = null): bool
    {
        return $this->create([
            'user_id'   => $userId,
            'tenant_id' => $tenantId,
            'title'     => $title,
            'message'   => $message,
            'type'      => $type,
            'link'      => $link,
            'is_read'   => 0
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(int $id, int $userId, int $tenantId): bool
    {
        return $this->query("
            UPDATE {$this->table} SET is_read = 1 
            WHERE id = :id AND user_id = :uid AND tenant_id = :tid
        ", [':id' => $id, ':uid' => $userId, ':tid' => $tenantId]);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(int $userId, int $tenantId): bool
    {
        return $this->query("
            UPDATE {$this->table} SET is_read = 1 
            WHERE user_id = :uid AND tenant_id = :tid
        ", [':uid' => $userId, ':tid' => $tenantId]);
    }
}
