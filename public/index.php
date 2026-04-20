<?php

declare(strict_types=1);

use App\Auth\AuthService;
use App\Controller\AppController;
use App\Core\Autoload;
use App\Core\Database;
use App\Core\Env;
use App\Repository\LessonRepository;
use App\Repository\MeetingRepository;
use App\Repository\UserRepository;
use App\Repository\ReportRepository;
use App\Service\MeetingService;
use App\Service\ProviderFactory;

require_once __DIR__ . '/../src/Core/Autoload.php';

Env::load(__DIR__ . '/../.env');
session_start();

$db = (new Database())->wpdb();

$userRepo = new UserRepository($db);
$meetingRepo = new MeetingRepository($db);
$lessonRepo = new LessonRepository($db);
$auth = new AuthService($userRepo);
$meetingService = new MeetingService($meetingRepo, $lessonRepo, new ProviderFactory(), $db);
$reportRepo = new ReportRepository($db);
$controller = new AppController($auth, $userRepo, $meetingRepo, $meetingService, $reportRepo);

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
