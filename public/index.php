<?php

declare(strict_types=1);

use App\Auth\AuthService;
use App\Controller\AppController;
use App\Core\Database;
use App\Repository\LessonRepository;
use App\Repository\MeetingRepository;
use App\Repository\UserRepository;
use App\Repository\ReportRepository;
use App\Service\MeetingService;
use App\Service\ProviderFactory;
use function App\Core\loadEnv;

require_once __DIR__ . '/../src/Core/Autoload.php';
require_once __DIR__ . '/../src/Core/Env.php';

$envData = loadEnv(__DIR__ . '/../.env');
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
session_start();

$db = (new Database($envData))->wpdb();

$userRepo = new UserRepository($db);
$meetingRepo = new MeetingRepository($db);
$lessonRepo = new LessonRepository($db);
$auth = new AuthService($userRepo);
$meetingService = new MeetingService($meetingRepo, $lessonRepo, new ProviderFactory($envData), $db);
$reportRepo = new ReportRepository($db);
$controller = new AppController($auth, $userRepo, $meetingRepo, $meetingService, $reportRepo);

$isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
if ($isHttps) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

switch ($path) {
    case '/login':
        $controller->login();
        break;
    case '/logout':
        $controller->logout();
        break;
    case '/meetings/create':
        $controller->meetingsCreate();
        break;
    case '/meetings/status':
        $controller->meetingsStatus();
        break;
    case '/webhooks/provider':
        $controller->webhook();
        break;
    case '/':
    default:
        $controller->dashboard();
        break;
}
