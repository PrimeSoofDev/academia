<?php
/**
 * routes/web.php — Application Route Definitions
 *
 * All web routes are registered here.
 * The $router variable is injected by App::run().
 *
 * Route syntax:
 *   $router->get('/path', 'ControllerName@method');
 *   $router->post('/path', 'ControllerName@method');
 *   $router->middleware('auth')->get('/protected', 'SomeController@index');
 *
 * Middleware options:
 *   'auth'      → must be logged in
 *   'guest'     → must NOT be logged in (for login page)
 *   'admin'     → superadmin or vc only
 *   'dean'      → dean and above
 *   'hod'       → hod and above
 *   'lecturer'  → lecturer and above
 */

// ─────────────────────────────────────────────
// 1. PUBLIC / AUTH ROUTES
// ─────────────────────────────────────────────

// Redirect root to dashboard (or login if unauthenticated)
$router->get('/', 'DashboardController@index');

// Show login form
$router->middleware('guest')->get('/login', 'AuthController@login');

// Process login submission
$router->middleware('guest')->post('/login', 'AuthController@authenticate');

// Logout
$router->middleware('auth')->get('/logout', 'AuthController@logout');


// ─────────────────────────────────────────────
// 2. DASHBOARD
// ─────────────────────────────────────────────

$router->middleware('auth')->get('/dashboard', 'DashboardController@index');

// Student Academic Records
$router->middleware('auth')->get('/records/transcript',  'RecordController@transcript');
$router->middleware('auth')->get('/records/certificate', 'RecordController@certificate');


// ─────────────────────────────────────────────
// 3. FACULTY ROUTES (VC / Dean and above)
// ─────────────────────────────────────────────

$router->middleware('dean')->get('/faculties',             'FacultyController@index');
$router->middleware('dean')->get('/faculties/create',      'FacultyController@create');
$router->middleware('dean')->post('/faculties/create',     'FacultyController@store');
$router->middleware('dean')->get('/faculties/{id}',        'FacultyController@show');
$router->middleware('dean')->get('/faculties/{id}/edit',   'FacultyController@edit');
$router->middleware('dean')->post('/faculties/{id}/edit',  'FacultyController@update');
$router->middleware('admin')->post('/faculties/{id}/delete','FacultyController@destroy');


// ─────────────────────────────────────────────
// 4. DEPARTMENT ROUTES (HOD and above)
// ─────────────────────────────────────────────

$router->middleware('hod')->get('/departments',              'DepartmentController@index');
$router->middleware('dean')->get('/departments/create',      'DepartmentController@create');
$router->middleware('dean')->post('/departments/create',     'DepartmentController@store');
$router->middleware('hod')->get('/departments/{id}',         'DepartmentController@show');
$router->middleware('hod')->get('/departments/{id}/edit',    'DepartmentController@edit');
$router->middleware('hod')->post('/departments/{id}/edit',   'DepartmentController@update');
$router->middleware('admin')->post('/departments/{id}/delete','DepartmentController@destroy');


// ─────────────────────────────────────────────
// 5. COURSE ROUTES
// ─────────────────────────────────────────────

$router->middleware('lecturer')->get('/courses',              'CourseController@index');
$router->middleware('hod')->get('/courses/create',            'CourseController@create');
$router->middleware('hod')->post('/courses/create',           'CourseController@store');
$router->middleware('lecturer')->get('/courses/{id}',         'CourseController@show');
$router->middleware('hod')->post('/courses/assign',           'CourseController@assign');
$router->middleware('hod')->get('/courses/{id}/edit',         'CourseController@edit');
$router->middleware('hod')->post('/courses/{id}/edit',        'CourseController@update');
$router->middleware('admin')->post('/courses/{id}/delete',    'CourseController@destroy');

// Student course enrollment
$router->middleware('auth')->get('/my-courses',               'CourseController@myCourses');
$router->middleware('auth')->post('/courses/{id}/enroll',     'CourseController@enroll');


// ─────────────────────────────────────────────
// 6. USER MANAGEMENT ROUTES
// ─────────────────────────────────────────────

$router->middleware('hod')->get('/users',              'UserController@index');
$router->middleware('admin')->get('/users/create',       'UserController@create');
$router->middleware('admin')->post('/users/create',      'UserController@store');
$router->middleware('hod')->get('/users/{id}',         'UserController@show');
$router->middleware('hod')->get('/users/{id}/edit',    'UserController@edit');
$router->middleware('hod')->post('/users/{id}/edit',   'UserController@update');
$router->middleware('admin')->post('/users/{id}/delete','UserController@destroy');


// ─────────────────────────────────────────────
// 7. PROFILE
// ─────────────────────────────────────────────

$router->middleware('auth')->get('/profile',       'ProfileController@show');
$router->middleware('auth')->post('/profile',      'ProfileController@update');

// ── Settings ──
$router->middleware('auth')->get('/settings',          'SettingsController@index');
$router->middleware('auth')->post('/settings/password', 'SettingsController@updatePassword');
$router->middleware('auth')->post('/settings/preferences', 'SettingsController@updatePreferences');

// ── Notifications ──
$router->middleware('auth')->get('/notifications',            'NotificationController@index');
$router->middleware('auth')->post('/notifications/read',      'NotificationController@markRead');
$router->middleware('auth')->post('/notifications/read-all',  'NotificationController@markAllRead');

// ── Academic Submissions ──
$router->middleware('auth')->get('/submissions',               'SubmissionController@index');
$router->middleware('lecturer')->get('/submissions/create',    'SubmissionController@create');
$router->middleware('lecturer')->post('/submissions/create',   'SubmissionController@store');
$router->middleware('auth')->get('/submissions/{id}',          'SubmissionController@show');
$router->middleware('staff')->post('/submissions/{id}/approve', 'SubmissionController@approve');

// ── Campus Live Reporting ──
$router->middleware('auth')->get('/reports',                  'ReportController@index');
$router->middleware('student')->post('/reports/create',       'ReportController@store');
$router->middleware('auth')->post('/reports/update-status',   'ReportController@updateStatus');

// ── Calendar & Meetings ──
$router->middleware('auth')->get('/calendar',                 'MeetingController@index');
$router->middleware('auth')->post('/calendar/book',           'MeetingController@book');
$router->middleware('auth')->post('/calendar/cancel',         'MeetingController@cancel');

// ── Results (role-aware: student sees transcript, staff/lecturer sees entry sheet) ──
$router->middleware('auth')->get('/results',                                      'ResultController@index');
$router->middleware('lecturer')->get('/results/courses/{id}',                     'ResultController@courseSheet');
$router->middleware('lecturer')->post('/results/courses/{id}/save',               'ResultController@saveCourseResults');
$router->middleware('lecturer')->post('/results/courses/{id}/publish',            'ResultController@publishResults');

// Course drop
$router->middleware('auth')->post('/courses/{id}/drop',                           'CourseController@drop');




// ─────────────────────────────────────────────
// 8. ADMINISTRATIVE UNIT ROUTES
// ─────────────────────────────────────────────

// Registry
$router->middleware('registry')->get('/registry',             'RegistryController@index');
$router->middleware('registry')->get('/registry/students',   'RegistryController@students');
$router->middleware('registry')->get('/registry/staff',      'RegistryController@staff');
$router->middleware('registry')->get('/registry/sessions',   'RegistryController@sessions');
$router->middleware('registry')->post('/registry/sessions',  'RegistryController@storeSession');
$router->middleware('registry')->post('/registry/sessions/set-current', 'RegistryController@setCurrentSession');
$router->middleware('registry')->post('/registry/admit',     'RegistryController@admit');

// Bursary
$router->middleware('bursary')->get('/bursary',              'BursaryController@index');
$router->middleware('bursary')->get('/bursary/payments',    'BursaryController@payments');
$router->middleware('bursary')->post('/bursary/record',     'BursaryController@record');

// Library
$router->middleware('library')->get('/library',              'LibraryController@index');
$router->middleware('library')->get('/library/books',       'LibraryController@books');
$router->middleware('library')->post('/library/books',      'LibraryController@storeBook');
