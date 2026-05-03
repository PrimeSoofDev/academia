<?php
/**
 * Application Configuration
 * Global app settings for the Academia SaaS system.
 */

return [
    'name'     => 'Academia',
    'version'  => '1.0.0',
    'env'      => 'development', // 'production' in live
    'debug'    => true,
    'url'      => 'http://localhost/academia/public',
    'timezone' => 'Africa/Lagos',

    // Session settings
    'session' => [
        'name'     => 'academia_session',
        'lifetime' => 3600, // 1 hour
    ],

    // Supported roles and their hierarchy levels
    'roles' => [
        'superadmin' => 0,
        'vc'         => 1,
        'dean'       => 2,
        'hod'        => 3,
        'lecturer'   => 4,
        'staff'      => 5,
        'student'    => 6,
    ],

    // Administrative units
    'units' => [
        'registry',
        'bursary',
        'library',
        'ict',
        'security',
        'health_services',
    ],
];
