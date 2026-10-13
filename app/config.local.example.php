<?php
/**
 * KSWEB local configuration template.
 *
 * Copy this file to config.local.php and edit the values.
 * config.local.php is ignored by Git; never commit real passwords.
 */
return [
    'APP_ENV' => 'development',
    'APP_MODE' => 'fixed',
    'LAB_ENABLED' => 'false',

    // Set a private username/password before enabling LAB_ENABLED.
    'LAB_USERNAME' => 'replace-with-your-lab-username',
    'LAB_PASSWORD' => 'replace-with-a-long-unique-password',

    // Use the database name/user/password configured in KSWEB.
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'teamsakit_lab',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
];
