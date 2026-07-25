<?php
/**
 * Global Delivered Logistics - Base Model
 * 
 * Provides CRUD operations, pagination, and query building
 * for all database models.
 */

namespace App\Core;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static array $searchable = [];
    protected static array $sortable = ['id', 'created_at'];
    protected static bool $softDelete = true;
    protected static string $deletedAt = 'deleted_at';

    protected Database $db;
    protected array $attributes = [];
    protected array $original = [];
    protected bool $exists = false;

    public function __construct(array $attributes = [])
    {
        $this->db = Database::getInstance();
        $this->fill($attributes);
    }

    /**
     * Fill model attributes
     */
    public function fill(array $attributes): self
    {
        foreach (static::$fillable as $field) {
            if (isset($attributes[$field])) {
                $this->attributes[$field] = $attributes[$field];
            }
        }
        return $this;
    }

    /**
     * Get table name
     */
    public static function table(): string
    {
        return static::$table;
    }

    /**
     * Find record by primary key
     */
    public static function find($id): ?static
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        
        if (static::$softDelete) {
            $sql .= " AND " . static::$deletedAt . " IS NULL";
        }
        
        $result = $db->fetch($sql, [$id]);
        
        if (!$result) return null;
        
        $model = new static();
        $model->attributes = (array) $result;
        $model->original = (array) $result;
        $model->exists = true;
        return $model;
    }

    /**
     * Find by a specific field
     */
    public static function findBy(string $field, $value): ?static
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM " . static::$table . " WHERE {$field} = ?";
        
        if (static::$softDelete) {
            $sql .= " AND " . static::$deletedAt . " IS NULL";
        }
        
        $result = $db->fetch($sql, [$value]);
        
        if (!$result) return null;
        
        $model = new static();
        $model->attributes = (array) $result;
        $model->original = (array) $result;
        $model->exists = true;
        return $model;
    }

    /**
     * Get all records
     */
    public static function all(array $conditions = [], string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM " . static::$table;
        $params = [];
        
        $wheres = [];
        if (static::$softDelete) {
            $wheres[] = static::$deletedAt . " IS NULL";
        }
        
        foreach ($conditions as $field => $value) {
            $wheres[] = "{$field} = ?";
            $params[] = $value;
        }
        
        if (!empty($wheres)) {
            $sql .= " WHERE " . implode(' AND ', $wheres);
        }
        
        $sql .= " ORDER BY {$orderBy} {$direction}";
        
        return $db->fetchAll($sql, $params);
    }

    /**
     * Paginate results
     */
    public static function paginate(int $page = 1, int $perPage = 25, array $conditions = [], string $search = ''): object
    {
        $db = Database::getInstance();
        $table = static::$table;
        
        $wheres = [];
        $params = [];
        
        if (static::$softDelete) {
            $wheres[] = "{$table}." . static::$deletedAt . " IS NULL";
        }
        
        foreach ($conditions as $field => $value) {
            $wheres[] = "{$table}.{$field} = ?";
            $params[] = $value;
        }
        
        if ($search && !empty(static::$searchable)) {
            $searchClauses = [];
            foreach (static::$searchable as $field) {
                $searchClauses[] = "{$table}.{$field} LIKE ?";
                $params[] = "%{$search}%";
            }
            $wheres[] = "(" . implode(' OR ', $searchClauses) . ")";
        }
        
        $whereClause = !empty($wheres) ? " WHERE " . implode(' AND ', $wheres) : '';
        
        $countSql = "SELECT COUNT(*) FROM {$table}{$whereClause}";
        $dataSql = "SELECT {$table}.* FROM {$table}{$whereClause} ORDER BY {$table}.id DESC";
        
        return $db->paginate($countSql, $dataSql, $params, $page, $perPage);
    }

    /**
     * Save model (insert or update)
     */
    public function save(): bool
    {
        try {
            if ($this->exists) {
                return $this->update();
            }
            return $this->insert();
        } catch (\Exception $e) {
            error_log("Model save error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Insert new record
     */
    protected function insert(): bool
    {
        $fields = [];
        $placeholders = [];
        $values = [];
        
        foreach (static::$fillable as $field) {
            if (isset($this->attributes[$field])) {
                $fields[] = $field;
                $placeholders[] = '?';
                $values[] = $this->attributes[$field];
            }
        }
        
        if (empty($fields)) return false;
        
        $sql = "INSERT INTO " . static::$table . " (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $this->db->query($sql, $values);
        $this->attributes[static::$primaryKey] = $this->db->lastInsertId();
        $this->exists = true;
        
        return true;
    }

    /**
     * Update existing record
     */
    protected function update(): bool
    {
        $sets = [];
        $values = [];
        
        foreach (static::$fillable as $field) {
            if (array_key_exists($field, $this->attributes) && 
                (!isset($this->original[$field]) || $this->attributes[$field] !== $this->original[$field])) {
                $sets[] = "{$field} = ?";
                $values[] = $this->attributes[$field];
            }
        }
        
        if (empty($sets)) return true;
        
        $values[] = $this->attributes[static::$primaryKey];
        $sql = "UPDATE " . static::$table . " SET " . implode(', ', $sets) . " 
                WHERE " . static::$primaryKey . " = ?";
        
        return $this->db->query($sql, $values)->rowCount() > 0;
    }

    /**
     * Delete record (soft or hard)
     */
    public function delete(): bool
    {
        if (!isset($this->attributes[static::$primaryKey])) return false;
        
        $id = $this->attributes[static::$primaryKey];
        
        if (static::$softDelete) {
            $sql = "UPDATE " . static::$table . " SET " . static::$deletedAt . " = NOW() WHERE " . static::$primaryKey . " = ?";
        } else {
            $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        }
        
        return $this->db->query($sql, [$id])->rowCount() > 0;
    }

    /**
     * Force delete (hard delete regardless of soft delete setting)
     */
    public function forceDelete(): bool
    {
        if (!isset($this->attributes[static::$primaryKey])) return false;
        $id = $this->attributes[static::$primaryKey];
        return $this->db->query("DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?", [$id])->rowCount() > 0;
    }

    /**
     * Count records
     */
    public static function count(array $conditions = []): int
    {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM " . static::$table;
        $params = [];
        
        $wheres = [];
        if (static::$softDelete) {
            $wheres[] = static::$deletedAt . " IS NULL";
        }
        
        foreach ($conditions as $field => $value) {
            $wheres[] = "{$field} = ?";
            $params[] = $value;
        }
        
        if (!empty($wheres)) {
            $sql .= " WHERE " . implode(' AND ', $wheres);
        }
        
        return (int) $db->fetchColumn($sql, $params);
    }

    /**
     * Sum a column
     */
    public static function sum(string $column, array $conditions = []): float
    {
        $db = Database::getInstance();
        $sql = "SELECT COALESCE(SUM({$column}), 0) FROM " . static::$table;
        $params = [];
        
        $wheres = [];
        if (static::$softDelete) {
            $wheres[] = static::$deletedAt . " IS NULL";
        }
        
        foreach ($conditions as $field => $value) {
            $wheres[] = "{$field} = ?";
            $params[] = $value;
        }
        
        if (!empty($wheres)) {
            $sql .= " WHERE " . implode(' AND ', $wheres);
        }
        
        return (float) $db->fetchColumn($sql, $params);
    }

    /**
     * Get attribute
     */
    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Set attribute
     */
    public function __set(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Check if attribute is set
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Convert model to array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Fresh instance from database
     */
    public function fresh(): ?static
    {
        if (!isset($this->attributes[static::$primaryKey])) return null;
        return static::find($this->attributes[static::$primaryKey]);
    }

    /**
     * Reload from database
     */
    public function reload(): static
    {
        $fresh = $this->fresh();
        if ($fresh) {
            $this->attributes = $fresh->attributes;
            $this->original = $fresh->original;
        }
        return $this;
    }
}
