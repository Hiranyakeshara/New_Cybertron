<?php
class EmployeeController extends Controller {
    public function create() {
        $model = $this->model('Employee');
        $departments = $model->getDepartments();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'department_id' => $_POST['department_id'] ?? '',
                'name' => $_POST['name'] ?? '',
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'nic' => $_POST['nic'] ?? '',
                'contact_number' => $_POST['contact_number'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];

            // Basic validation (optional: enhance this)
            if (in_array('', $data)) {
                $error = "All fields are required.";
            } else {
                $model->create($data);
                header("Location: /New_Cybertron/admin/public/employee/create?success=1");
                exit;
            }
        }

        $this->view('employee/create', [
            'departments' => $departments,
            'error' => $error ?? null,
            'success' => $_GET['success'] ?? null
        ]);
    }


    public function viewAll() {
    $model = $this->model('Employee');
    $employees = $model->getAllWithDepartments();

    $this->view('employee/view', ['employees' => $employees]);
}

}
