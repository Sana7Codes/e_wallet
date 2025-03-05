<?php

require_once __DIR__ . '/../config/connection.php';

class BaseModel
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // CREATE 
    public function create($table, $data)
    {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $types = $this->getBindTypes($data);

            // Prepare statement
            $stmt = $this->conn->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");

            if (!$stmt) throw new Exception("Prepare failed: " . $this->conn->error);
            // Bind parameters and execute 
            $stmt->bind_param($types, ...array_values($data));
            $stmt->execute();

            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw ($e);
            return false;
        }
    }

    // READ 
    public function find($table, $id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $table WHERE id = ?");
        if (!$stmt) return null;

        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // UPDATE 
    public function update($table, $data, $id)
    {
        try {
            $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';
            $types = $this->getBindTypes($data) . 'i';

            $stmt = $this->conn->prepare("UPDATE $table SET $setClause WHERE id = ?");
            if (!$stmt) throw new Exception("Prepare failed: " . $this->conn->error);

            $params = array_merge(array_values($data), [$id]);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();

            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // DELETE 
    public function delete($table, $id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM $table WHERE id = ?");
            if (!$stmt) throw new Exception("Prepare failed: " . $this->conn->error);

            $stmt->bind_param('i', $id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // EXISTS Check
    public function exists($table, $column, $value)
    {
        $stmt = $this->conn->prepare("SELECT id FROM $table WHERE $column = ? LIMIT 1");
        if (!$stmt) return false;

        $stmt->bind_param('s', $value);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Determine Binding Types Automatically
    private function getBindTypes($data)
    {
        $types = '';
        foreach ($data as $value) {
            if (is_int($value)) $types .= 'i';
            elseif (is_double($value)) $types .= 'd';
            else $types .= 's';
        }
        return $types;
    }
}
