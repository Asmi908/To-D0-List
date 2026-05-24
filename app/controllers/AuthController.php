<?php
 
namespace App\controllers;
 
use App\core\BaseController;
use App\models\Users;
 
class AuthController extends BaseController
{
    private Users $userModel;
 
    public function __construct()
    {
        $this->userModel = new Users();
 
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
 
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
 
            if (empty($email) || empty($password)) {
                return $this->loadView('auth/login', ['error' => 'Email and password required.']);
            }
 
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->loadView('auth/login', ['error' => 'Invalid email format.']);
            }
 
            $user = $this->userModel->findByEmail($email);
 
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'] ?? null;
                $_SESSION['username'] = $user['username'];
                $this->redirect('?url=category/index');
            } else {
                $this->loadView('auth/login', ['error' => 'Invalid credentials.']);
            }
        } else {
            $this->loadView('auth/login');
        }
    }
 
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
 
            if (empty($username) || empty($email) || empty($password)) {
                $this->loadView('auth/register', ['error' => 'All fields are required.']);
                return;
            }
 
            if (strlen($username) < 3) {
                $this->loadView('auth/register', ['error' => 'Username must be at least 3 characters long.']);
                return;
            }
 
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->loadView('auth/register', ['error' => 'Invalid email format.']);
                return;
            }
 
            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
                $this->loadView('auth/register', ['error' => 'Password must be at least 8 characters long and contain at least one letter and one number.']);
                return;
            }
 
            if ($this->userModel->findByEmail($email)) {
                $this->loadView('auth/register', ['error' => 'Email already exists.']);
                return;
            }
 
            if ($this->userModel->create($username, $email, $password)) {
                $_SESSION['message'] = 'Registration successful. Please log in.';
                $this->redirect('?url=auth/login');
            } else {
                $this->loadView('auth/register', ['error' => 'Registration failed.']);
            }
        } else {
            $this->loadView('auth/register');
        }
    }
 
    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
 
            if (empty($email)) {
                return $this->loadView('auth/forgotPassword', ['error' => 'Email is required.']);
            }
 
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->loadView('auth/forgotPassword', ['error' => 'Invalid email format.']);
            }
 
            $user = $this->userModel->findByEmail($email);
            if (!$user) {
                return $this->loadView('auth/forgotPassword', ['error' => 'Email not found.']);
            }
 
            $_SESSION['reset_email'] = $email;
            $this->redirect('?url=auth/resetPassword');
        } else {
            $this->loadView('auth/forgotPassword');
        }
    }
 
    public function resetPassword()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
 
        if (empty($email) || empty($password)) {
            return $this->loadView('auth/resetPassword', ['error' => 'Email and password required.']);
        }
 
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
            return $this->loadView('auth/resetPassword', ['error' => 'Password must be at least 8 characters long and contain at least one letter and one number.']);
        }
 
        $user = $this->userModel->findByEmail($email);
        if ($user) {
            if ($this->userModel->updatePassword($user['user_id'], $password)) {
                unset($_SESSION['reset_email']);
                $_SESSION['message'] = 'Password reset successful. Please log in.';
                $this->redirect('?url=auth/login');
            } else {
                return $this->loadView('auth/resetPassword', ['error' => 'Failed to reset password.']);
            }
        } else {
            return $this->loadView('auth/resetPassword', ['error' => 'Invalid email.']);
        }
    } else {
        if (!isset($_SESSION['reset_email'])) {
            $this->redirect('?url=auth/forgotPassword');
        }
        $this->loadView('auth/resetPassword');
    }
}
 
 
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $currentPassword = $_POST['currentPassword'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';
            $userId = $_SESSION['user_id'];
 
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                return $this->loadView('note/note_detail', ['error' => 'All fields are required.']);
            }
 
            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $newPassword)) {
                return $this->loadView('note/note_detail', ['error' => 'New password must be at least 8 characters long and contain at least one letter and one number.']);
            }
 
            if ($newPassword !== $confirmPassword) {
                return $this->loadView('note/note_detail', ['error' => 'New password and confirm password do not match.']);
            }
 
            $user = $this->userModel->getById($userId);
            if ($user && password_verify($currentPassword, $user['password'])) {
                if ($this->userModel->updatePassword($userId, $newPassword)) {
                    return $this->loadView('note/note_detail', ['message' => 'Password updated successfully.']);
                } else {
                    return $this->loadView('note/note_detail', ['error' => 'Failed to update password.']);
                }
            } else {
                return $this->loadView('note/note_detail', ['error' => 'Current password is incorrect.']);
            }
        } else {
            $this->redirect('?url=note/note_detail');
        }
    }
 
    public function deleteAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
 
            if ($this->userModel->deleteUser($userId)) {
                session_destroy();
                $this->redirect('?url=auth/login', ['message' => 'Account deleted successfully.']);
            } else {
                $this->loadView('note/note_detail', ['error' => 'Failed to delete account.']);
            }
        } else {
            $this->redirect('?url=auth/login');
        }
    }
 
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
 
        $this->redirect('?url=auth/login');
    }
}