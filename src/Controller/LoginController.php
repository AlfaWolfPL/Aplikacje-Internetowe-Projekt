<?php
namespace App\Controller;
class LoginController {
    private $admin_user = "admin";
    private $admin_pass = "tajnehaslo123";

    public function login() {
        session_start();

        if (isset($_SESSION['admin_logged_in'])) {
            header("Location: /index.php?action=admin-index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($username === $this->admin_user && $password === $this->admin_pass) {
                $_SESSION['admin_logged_in'] = true;
                header("Location: /index.php?action=admin-index");
                exit;
            } else {
                $error = "Nieprawidłowy login lub hasło.";
            }
        }

        require_once __DIR__ . '/../../templates/admin/login.html.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /index.php?action=movie-index");
        exit;
    }

    public static function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: /index.php?action=login");
            exit;
        }
    }
}