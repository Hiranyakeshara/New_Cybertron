<?php
require_once '../core/Database.php';

class Feedback extends Database {
    public function getAllFeedback() {
        $stmt = $this->connect()->prepare("SELECT * FROM feedback");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

