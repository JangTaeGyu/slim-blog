<?php
$data = require 'src/settings.php';

return [
    'paths' => [
        'migrations' => 'database/migrations'
    ],
    'migration_base_class' => '\App\Migration\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => $data['settings']['db']['driver'],
            'host' => $data['settings']['db']['host'],
            'name' => $data['settings']['db']['database'],
            'user' => $data['settings']['db']['username'],
            'pass' => $data['settings']['db']['password'],
            'port' => 3306
        ]
    ]
];
