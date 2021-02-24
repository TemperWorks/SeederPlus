<?php

use Temper\SeederPlus\ModelFinders\FindById;

return [
    'snapshot_storage_path' => base_path('vendor/temper/seederplus/storage/snapshots/'),
    'storage_file' => base_path('vendor/temper/seederplus/storage/seederplus.json'),
    'base_snapshot' => base_path('vendor/temper/seederplus/storage/base-snapshot.sql'),

    'seeder_directories' => [
        database_path().DIRECTORY_SEPARATOR.'seeds', // <= Laravel 7
        database_path().DIRECTORY_SEPARATOR.'seeders', // >= Laravel 8
    ],

    'relation_finders' => [
        FindById::class => 'Find by id'
    ]
];
