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

    //update employee details
     public function update($data) {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE employees SET 
                        name = :name,
                        username = :username,
                        nic = :nic,
                        contact_number = :contact_number,
                        email = :email,
                        password = :password
                    WHERE id = :id";
        } else {
            $sql = "UPDATE employees SET 
                        name = :name,
                        username = :username,
                        nic = :nic,
                        contact_number = :contact_number,
                        email = :email
                    WHERE id = :id";
        }

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($data);
    }

    //delete employee details
    public function delete($id) {
        $stmt = $this->dbh->prepare("DELETE FROM employees WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getDepartments() {
        $stmt = $this->dbh->query("SELECT id, department_code, department_name FROM departments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithDepartments() {
    $stmt = $this->dbh->query("
        SELECT employees.id, employees.name, employees.username, employees.nic, employees.contact_number,departments.department_code, departments.department_name, employees.email
        FROM employees
        JOIN departments ON employees.department_id = departments.id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
