<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Web UI Routes for Authentication
$routes->group('auth', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->get('register', 'AuthController::register');
    $routes->get('forgot-password', 'AuthController::forgotPassword');
    $routes->get('reset-password', 'AuthController::resetPassword');
    $routes->get('verify-email', 'AuthController::verifyEmail');
});

// API Routes for Authentication (Rate limited: 5 req per 60 seconds for sensitive actions)
$routes->group('api/auth', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
    $routes->post('register', 'RegisterController::index', ['filter' => 'throttle:5,60']);
    $routes->post('login', 'LoginController::index', ['filter' => 'throttle:5,60']);
    $routes->post('logout', 'LoginController::logout', ['filter' => 'jwt']);
    $routes->post('refresh-token', 'LoginController::refreshToken');
    $routes->post('forgot-password', 'PasswordController::forgot', ['filter' => 'throttle:5,60']);
    $routes->post('reset-password', 'PasswordController::reset', ['filter' => 'throttle:5,60']);
    $routes->post('verify-email', 'VerificationController::verify', ['filter' => 'throttle:10,60']);
});

// Alumni Dashboard Routes (Requires JWT Auth in production, for now just standard routing)
// Wait, I will apply 'filter' => 'jwt' when the frontend is fully ready, but since it's a view, we need a web auth filter or session. 
// Since this is a view-driven app, the JWT is stored in an HttpOnly cookie. The `jwt` filter checks this cookie.
$routes->group('alumni', ['namespace' => 'App\Controllers\Alumni', 'filter' => ['jwt', 'role:2']], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('profile', 'ProfileController::index');
});

// API Routes for Dashboard Data
$routes->group('api/alumni', ['namespace' => 'App\Controllers\Alumni', 'filter' => ['jwt', 'role:2']], static function ($routes) {
    $routes->get('dashboard/stats', 'DashboardController::getStats');
    $routes->get('dashboard/history', 'DashboardController::getGlobalHistory');
    
    // Profile APIs
    $routes->get('profile/data', 'ProfileController::getData');
    $routes->post('profile/general', 'ProfileController::updateGeneral');
    $routes->post('profile/upload-photo', 'ProfileController::uploadPhoto');
    
    $routes->post('profile/degree', 'ProfileController::saveDegree');
    $routes->delete('profile/degree/(:num)', 'ProfileController::deleteDegree/$1');
    
    $routes->post('profile/employment', 'ProfileController::saveEmployment');
    $routes->delete('profile/employment/(:num)', 'ProfileController::deleteEmployment/$1');
    
    $routes->post('profile/certification', 'ProfileController::saveCertification');
    $routes->delete('profile/certification/(:num)', 'ProfileController::deleteCertification/$1');
    
    $routes->post('profile/licence', 'ProfileController::saveLicence');
    $routes->delete('profile/licence/(:num)', 'ProfileController::deleteLicence/$1');
    
    $routes->post('profile/course', 'ProfileController::saveCourse');
    $routes->delete('profile/course/(:num)', 'ProfileController::deleteCourse/$1');
    $routes->post('profile/project', 'ProfileController::saveProject');
    $routes->delete('profile/project/(:num)', 'ProfileController::deleteProject/$1');
    $routes->post('profile/achievement', 'ProfileController::saveAchievement');
    $routes->delete('profile/achievement/(:num)', 'ProfileController::deleteAchievement/$1');
    


    // Blind Bids
    $routes->post('bid', 'BlindBidController::submit');
    $routes->get('bid/history', 'BlindBidController::history');
});

// Global Authenticated Routes (Accessible to all logged-in users)
$routes->get('directory', 'Alumni\DirectoryController::index', ['filter' => 'jwt']);
$routes->get('alumni/profile/(:num)', 'Alumni\DirectoryController::publicProfile/$1', ['filter' => 'jwt']);

// Sponsorship (Called from directory, requires sponsor role)
$routes->post('api/alumni/sponsor', 'Alumni\SponsorController::submit', ['filter' => ['jwt', 'role:4']]);
$routes->get('api/alumni/sponsor/history/(:num)', 'Alumni\SponsorController::history/$1', ['filter' => ['jwt', 'role:4']]);

// Sponsor Routes
$routes->group('sponsor', ['namespace' => 'App\Controllers\Sponsor', 'filter' => ['jwt', 'role:4']], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('profile', 'ProfileController::index');
});

$routes->group('api/sponsor', ['namespace' => 'App\Controllers\Sponsor', 'filter' => ['jwt', 'role:4']], static function ($routes) {
    $routes->get('dashboard/stats', 'DashboardController::getStats');
    $routes->get('dashboard/history', 'DashboardController::getGlobalHistory');
    
    // Profile APIs
    $routes->get('profile/data', 'ProfileController::getData');
    $routes->post('profile/general', 'ProfileController::updateGeneral');
    $routes->post('profile/upload-photo', 'ProfileController::uploadPhoto');
});

// Student Routes
$routes->group('student', ['namespace' => 'App\Controllers\Student', 'filter' => ['jwt', 'role:3']], static function ($routes) {
    $routes->get('favorites', 'FavoriteController::index');
    $routes->get('profile', 'ProfileController::index');
});

$routes->group('api/student', ['filter' => ['jwt', 'role:3']], static function ($routes) {
    // Favorites API
    $routes->get('favorite/list', '\App\Controllers\Student\FavoriteController::list');
    $routes->post('favorite/toggle', '\App\Controllers\Student\FavoriteController::toggle');
    
    // Profile APIs - reusing Alumni ProfileController logic
    $routes->get('profile/data', '\App\Controllers\Alumni\ProfileController::getData');
    $routes->post('profile/general', '\App\Controllers\Alumni\ProfileController::updateGeneral');
    $routes->post('profile/upload-photo', '\App\Controllers\Alumni\ProfileController::uploadPhoto');
    
    $routes->post('profile/degree', '\App\Controllers\Alumni\ProfileController::saveDegree');
    $routes->delete('profile/degree/(:num)', '\App\Controllers\Alumni\ProfileController::deleteDegree/$1');
    
    $routes->post('profile/employment', '\App\Controllers\Alumni\ProfileController::saveEmployment');
    $routes->delete('profile/employment/(:num)', '\App\Controllers\Alumni\ProfileController::deleteEmployment/$1');
    
    $routes->post('profile/certification', '\App\Controllers\Alumni\ProfileController::saveCertification');
    $routes->delete('profile/certification/(:num)', '\App\Controllers\Alumni\ProfileController::deleteCertification/$1');
    
    $routes->post('profile/licence', '\App\Controllers\Alumni\ProfileController::saveLicence');
    $routes->delete('profile/licence/(:num)', '\App\Controllers\Alumni\ProfileController::deleteLicence/$1');
    
    $routes->post('profile/course', '\App\Controllers\Alumni\ProfileController::saveCourse');
    $routes->delete('profile/course/(:num)', '\App\Controllers\Alumni\ProfileController::deleteCourse/$1');
    $routes->post('profile/project', '\App\Controllers\Alumni\ProfileController::saveProject');
    $routes->delete('profile/project/(:num)', '\App\Controllers\Alumni\ProfileController::deleteProject/$1');
    $routes->post('profile/achievement', '\App\Controllers\Alumni\ProfileController::saveAchievement');
    $routes->delete('profile/achievement/(:num)', '\App\Controllers\Alumni\ProfileController::deleteAchievement/$1');
});

$routes->group('api', ['filter' => 'jwt'], static function ($routes) {
    // Directory API
    $routes->get('directory', 'Alumni\DirectoryController::apiList');
});

// Settings Web Route
$routes->get('settings', 'SettingsController::index', ['filter' => 'jwt']);

// Admin Routes
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => ['jwt', 'role:1', 'desktop_only']], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('users', 'UserController::index');
    $routes->get('activities', 'ActivityController::index');
});

$routes->group('api/admin', ['namespace' => 'App\Controllers\Admin', 'filter' => ['jwt', 'role:1', 'desktop_only']], static function ($routes) {
    // Activities
    $routes->get('activities/list', 'ActivityController::list');
    // Analytics
    $routes->get('analytics/top-certifications', 'AnalyticsController::topCertifications');
    $routes->get('analytics/emerging-pathways', 'AnalyticsController::emergingPathways');
    $routes->get('analytics/industry-distribution', 'AnalyticsController::industryDistribution');
    $routes->get('analytics/certification-growth', 'AnalyticsController::certificationGrowth');
    $routes->get('analytics/skills-curriculum', 'AnalyticsController::skillsCurriculum');
    $routes->get('analytics/professional-courses', 'AnalyticsController::professionalCourses');
    $routes->get('analytics/employment-status', 'AnalyticsController::employmentStatus');
    $routes->get('analytics/user-roles', 'AnalyticsController::userRoles');

    // Analytics Presets & Exports
    $routes->get('analytics/presets', 'AnalyticsController::loadPresets');
    $routes->post('analytics/presets', 'AnalyticsController::savePreset');
    $routes->delete('analytics/presets/(:num)', 'AnalyticsController::deletePreset/$1');
    $routes->get('analytics/export-csv', 'AnalyticsController::exportCsv');
    $routes->post('analytics/export-pdf', 'AnalyticsController::exportPdf');

    // Users
    $routes->get('users/list', 'UserController::list');
    $routes->post('users/status', 'UserController::updateStatus');
    $routes->post('users/role', 'UserController::updateRole');
});

// Settings API Routes
$routes->group('api/settings', ['filter' => 'jwt'], static function ($routes) {
    $routes->post('email/initiate', 'SettingsController::initiateEmailChange', ['filter' => 'throttle:5,60']);
    $routes->post('email/verify-current', 'SettingsController::verifyCurrentEmail', ['filter' => 'throttle:5,60']);
    $routes->post('email/initiate-new', 'SettingsController::initiateNewEmail', ['filter' => 'throttle:5,60']);
    $routes->post('email/verify-new', 'SettingsController::verifyNewEmail', ['filter' => 'throttle:5,60']);
    
    $routes->post('password/initiate', 'SettingsController::initiatePasswordChange', ['filter' => 'throttle:5,60']);
    $routes->post('password/verify-otp', 'SettingsController::verifyPasswordOtp', ['filter' => 'throttle:5,60']);
    $routes->post('password', 'SettingsController::updatePassword', ['filter' => 'throttle:5,60']);
    $routes->delete('account', 'SettingsController::deleteAccount');
    $routes->post('photo', 'SettingsController::updatePhoto');
});
