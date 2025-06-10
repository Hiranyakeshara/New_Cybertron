<?php
require_once '../core/Database.php';
class Department extends Database {
   public function create($code, $name) {
        $stmt = $this->dbh->prepare("INSERT INTO departments (department_code, department_name) VALUES (?, ?)");
        return $stmt->execute([$code, $name]);
    }

    public function getAll() {
        $stmt = $this->dbh->query("SELECT * FROM departments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}