<?php

declare(strict_types=1);

namespace App\Auth;

use App\Repository\UserRepository;

final class AuthService
{
    public function __construct(private UserRepository $users)
    {
    }

    public function login(string $userLogin, string $password): bool
    {
        $user = $this->users->findByLogin($userLogin);
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['user_pass'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['auth_user'] = [
            'ID' => (int) $user['ID'],
            'user_login' => $user['user_login'],
            'display_name' => $user['display_name'],
            'role' => $user['app_role'] ?? 'peer',
        ];

        return true;
    }

    public function user(): ?array
    {
        return $_SESSION['auth_user'] ?? null;
    }

    public function requireRole(array $roles): void
    {
        $user = $this->user();
        if (!$user || !in_array($user['role'], $roles, true)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
