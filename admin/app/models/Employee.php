<?php
require_once '../core/Database.php';

class Employee extends Database {
    public function create($data) {
        $stmt = $this->dbh->prepare("
            INSERT INTO employees (department_id, name, username, password, nic, contact_number, email)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['department_id'],
            $data['name'],
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['nic'],
            $data['contact_number'],
            $data['email']
        ]);
    }

    public function getDepartments() {
        $stmt = $this->dbh->query("SELECT id, department_name FROM departments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithDepartments() {
    $stmt = $this->dbh->query("
        SELECT employees.id, employees.name, employees.username, employees.nic, employees.contact_number, departments.department_name, employees.email
        FROM employees
        JOIN departments ON employees.department_id = departments.id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
