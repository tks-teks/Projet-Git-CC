<?php
namespace App\Controllers;

use App\Models\Pharmacie;
use App\Services\NotificationService;

class AuthController
{
    private $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    public function login($request)
    {
        // Logic for handling user login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $request['username'] ?? '';
            $password = $request['password'] ?? '';

            // Validate credentials and authenticate user
            // This is a placeholder for actual authentication logic
            if ($this->authenticate($username, $password)) {
                $_SESSION['pharmacie_id'] = $username; // Example, replace with actual user ID
                header('Location: dashboard.php');
                exit;
            } else {
                $this->notificationService->addError('Invalid username or password.');
            }
        }

        // Load login view
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function logout()
    {
        // Logic for handling user logout
        session_start();
        session_destroy();
        header('Location: login.php');
        exit;
    }

    private function authenticate($username, $password)
    {
        // Placeholder for actual authentication logic
        // This should check the credentials against the database
        return $username === 'admin' && $password === 'password'; // Example credentials
    }
}
?>