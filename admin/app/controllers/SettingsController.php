<?php
class SettingsController extends Controller {
    public function index() {
        $platformInfo = [
            'Platform Name' => 'Cybertrone Admin Portal',
            'Version' => 'v1.2.0',
            'Developed By' => 'Cybertrone Dev Team',
            'Framework' => 'PHP MVC (Custom Lightweight)',
            'PHP Version' => phpversion(),
            'Database' => 'MySQL 8.0+',
            'Web Server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'Client OS' => PHP_OS,
            'App Path' => realpath(__DIR__ . '/../../../')
        ];

        $this->view('settings/view', ['platformInfo' => $platformInfo]);
    }
}
