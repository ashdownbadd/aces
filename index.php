<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('ALLOW_ACCESS', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

$app = require __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

/*
|--------------------------------------------------------------------------
| Core Helpers
|--------------------------------------------------------------------------
| These provide the application's core infrastructure.
| They should be loaded before feature-specific helpers.
*/

require_once __DIR__ . '/helpers/View.php';
require_once __DIR__ . '/helpers/Url.php';
require_once __DIR__ . '/helpers/Response.php';

/*
|--------------------------------------------------------------------------
| Session & Authentication
|--------------------------------------------------------------------------
| Authentication depends on sessions and redirects.
*/

require_once __DIR__ . '/helpers/Session.php';
require_once __DIR__ . '/helpers/Flash.php';
require_once __DIR__ . '/helpers/Redirect.php';
require_once __DIR__ . '/helpers/Auth.php';

/*
|--------------------------------------------------------------------------
| Application Helpers
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/helpers/Logger.php';

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/MemberController.php';
require_once __DIR__ . '/controllers/LedgerController.php';
require_once __DIR__ . '/controllers/AmortizationController.php';
require_once __DIR__ . '/controllers/ActivityController.php';

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/routes/web.php';
