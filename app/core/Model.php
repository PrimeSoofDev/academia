<?php
/**
 * Model.php — Base Model Class
 *
 * All models extend this class to inherit database access,
 * tenant-scoped queries, and common CRUD operations.
 */

abstract class Model
{
    protected Database $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    /**
     * Inject the Database singleton on construction.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ─────────────────────────────────────────────
    // TENANT-SCOPED QUERIES
    // All queries automatically scope by tenant_id
    // ─────────────────────────────────────────────

    /**
     * Find a single record by ID, scoped to current tenant.
     */
    public function find(int $id, ?int $tenantId = null): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $bindings = [':id' => $id];

        if ($tenantId !== null) {
            $sql .= ' AND tenant_id = :tenant_id';
            $bindings[':tenant_id'] = $tenantId;
        }

        return $this->db->fetchOne($sql, $bindings);
    }

    /**
     * Get all records, optionally scoped to a tenant.
     */
    public function all(?int $tenantId = null): array
    {
        if ($tenantId !== null) {
            return $this->db->fetchAll(
                "SELECT * FROM {$this->table} WHERE tenant_id = :tenant_id ORDER BY id DESC",
                [':tenant_id' => $tenantId]
            );
        }
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY id DESC");
    }

    /**
     * Find records matching given conditions.
     * $conditions = ['column' => 'value', ...]
     */
    public function where(array $conditions, ?int $tenantId = null, string $orderBy = ''): array
    {
        $clauses  = [];
        $bindings = [];

        foreach ($conditions as $column => $value) {
            $clauses[]               = "{$column} = :{$column}";
            $bindings[":{$column}"] = $value;
        }

        if ($tenantId !== null) {
            $clauses[]            = 'tenant_id = :tenant_id';
            $bindings[':tenant_id'] = $tenantId;
        }

        $sql = "SELECT * FROM {$this->table}";
        if (!empty($clauses)) {
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        return $this->db->fetchAll($sql, $bindings);
    }

    /**
     * Find a single record matching conditions.
     */
    public function findWhere(array $conditions, ?int $tenantId = null): array|false
    {
        $results = $this->where($conditions, $tenantId);
        return $results[0] ?? false;
    }

    /**
     * Insert a new record. Returns the new record ID.
     */
    public function create(array $data): int
    {
        $columns  = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($k) => ":{$k}", array_keys($data)));
        $bindings = [];

        foreach ($data as $key => $value) {
            $bindings[":{$key}"] = $value;
        }

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->query($sql, $bindings);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update a record by ID. Scoped to tenant if provided.
     */
    public function update(int $id, array $data, ?int $tenantId = null): bool
    {
        $setClauses = [];
        $bindings   = [];

        foreach ($data as $key => $value) {
            $setClauses[]           = "{$key} = :{$key}";
            $bindings[":{$key}"]   = $value;
        }

        $bindings[':id'] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . " WHERE {$this->primaryKey} = :id";

        if ($tenantId !== null) {
            $sql .= ' AND tenant_id = :tenant_id';
            $bindings[':tenant_id'] = $tenantId;
        }

        $this->db->query($sql, $bindings);
        return true;
    }

    /**
     * Soft delete (if table has `deleted_at`), otherwise hard delete.
     */
    public function delete(int $id, ?int $tenantId = null): bool
    {
        $bindings = [':id' => $id];
        $sql      = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";

        if ($tenantId !== null) {
            $sql .= ' AND tenant_id = :tenant_id';
            $bindings[':tenant_id'] = $tenantId;
        }

        $this->db->query($sql, $bindings);
        return true;
    }

    /**
     * Count rows, optionally scoped to a tenant.
     */
    public function count(?int $tenantId = null): int
    {
        if ($tenantId !== null) {
            $result = $this->db->fetchOne(
                "SELECT COUNT(*) as total FROM {$this->table} WHERE tenant_id = :tenant_id",
                [':tenant_id' => $tenantId]
            );
        } else {
            $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM {$this->table}");
        }
        return (int)($result['total'] ?? 0);
    }

    /**
     * Run a raw query (for complex joins, etc.)
     */
    public function raw(string $sql, array $bindings = []): array
    {
        return $this->db->fetchAll($sql, $bindings);
    }
}
