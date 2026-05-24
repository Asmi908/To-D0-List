<?php
namespace App\core;

class BaseController {
    public function __construct() {
        // Skip session_start if running in a test environment
        if (!defined('PHPUNIT_TESTING')) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }
    }

    protected function loadView(string $viewPath, array $data = []): void {
        extract($data);
        require __DIR__ . '/../views/' . $viewPath . '.php';
    }

    protected function redirect(string $path): void {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function requireAuth(): int {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('?url=auth/login');
        }
        return (int) $_SESSION['user_id'];
    }

    protected function setFlash(string $key, string $message): void {
        $_SESSION['flash'][$key] = $message;
    }

    protected function getFlash(string $key): ?string {
        if (!empty($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }

    protected function ensureCsrfToken(): void {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    protected function verifyCsrfToken(string $token): void {
        if (
            empty($token)
            || !isset($_SESSION['csrf_token'])
            || !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            throw new \RuntimeException("Invalid CSRF token");
        }
    }
}