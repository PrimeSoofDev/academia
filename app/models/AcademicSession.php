<?php
/**
 * AcademicSession.php
 */

require_once ROOT_PATH . '/app/core/Model.php';

class AcademicSession extends Model
{
    protected string $table = 'academic_sessions';

    /**
     * Get the currently active academic session.
     */
    public function getCurrentSession(): ?array
    {
        $result = $this->db->fetchOne("
            SELECT * FROM {$this->table} 
            WHERE tenant_id = :tenant_id AND is_current = 1 
            LIMIT 1
        ", [':tenant_id' => Auth::tenantId()]);
        return $result ?: null;
    }

    /**
     * Set a session as current, automatically unsetting others.
     */
    public function setAsCurrent(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            // Unset all
            $this->db->query("UPDATE {$this->table} SET is_current = 0 WHERE tenant_id = :tenant_id", 
                [':tenant_id' => Auth::tenantId()]
            );

            // Set the target
            $this->db->query("UPDATE {$this->table} SET is_current = 1 WHERE id = :id AND tenant_id = :tenant_id", [
                ':id' => $id,
                ':tenant_id' => Auth::tenantId()
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error setting current session: " . $e->getMessage());
            return false;
        }
    }
}
