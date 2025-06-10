<?php
class DepartmentController extends Controller {

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['department_code'] ?? '';
            $name = $_POST['department_name'] ?? '';

            if (!empty($code) && !empty($name)) {
                $model = $this->model('Department');
                $model->create($code, $name);
                header("Location: /New_Cybertron/admin/public/department/create?success=1");
                exit;
            } else {
                $error = "All fields are required.";
            }
        }

        $data = [
            'error' => $error ?? null,
            'success' => $_GET['success'] ?? null
        ];

        $this->view('department/create', $data);
    }

    // ✅ Rename this method to avoid overriding base view() method
    public function viewDepartments() {
        $model = $this->model('Department');
        $departments = $model->getAll();

        $this->view('department/view', ['departments' => $departments]);
    }
}
