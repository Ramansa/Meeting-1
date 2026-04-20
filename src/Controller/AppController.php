<?php

declare(strict_types=1);

namespace App\Controller;

use App\Auth\AuthService;
use App\Repository\MeetingRepository;
use App\Repository\UserRepository;
use App\Repository\ReportRepository;
use App\Service\MeetingService;

final class AppController
{
    public function __construct(
        private AuthService $auth,
        private UserRepository $users,
        private MeetingRepository $meetings,
        private MeetingService $meetingService,
        private ReportRepository $reports
    ) {
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $this->auth->login($_POST['user_login'] ?? '', $_POST['password'] ?? '');
            if ($ok) {
                header('Location: /');
                return;
            }
            $error = 'Invalid credentials';
            include __DIR__ . '/../../templates/login.php';
            return;
        }

        include __DIR__ . '/../../templates/login.php';
    }

    public function logout(): void
    {
        $this->auth->logout();
        header('Location: /login');
    }

    public function dashboard(): void
    {
        $user = $this->auth->user();
        if (!$user) {
            header('Location: /login');
            return;
        }

        $meetings = $this->meetings->forCalendar((int)$user['ID'], $user['role']);
        $report = $this->reports->summary();
        include __DIR__ . '/../../templates/dashboard.php';
    }

    public function meetingsCreate(): void
    {
        $this->auth->requireRole(['admin', 'teacher']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->meetingService->create($_POST);
                header('Location: /?saved=1');
                return;
            } catch (\Throwable $e) {
                $error = $e->getMessage();
            }
        }

        $teachers = $this->users->findByRole('teacher');
        $students = $this->users->findByRole('peer');
        include __DIR__ . '/../../templates/meeting_form.php';
    }

    public function meetingsStatus(): void
    {
        $this->auth->requireRole(['admin']);
        $id = (int)($_POST['meeting_id'] ?? 0);
        $status = (string)($_POST['status'] ?? 'Not Verified');
        $this->meetingService->setStatus($id, $status);
        header('Location: /');
    }

    public function webhook(): void
    {
        $payload = file_get_contents('php://input') ?: '';
        file_put_contents(__DIR__ . '/../../storage/logs/webhook.log', gmdate('c') . ' ' . $payload . PHP_EOL, FILE_APPEND);
        echo json_encode(['ok' => true]);
    }
}
