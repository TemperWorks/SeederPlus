<?php

return [
    'snapshot_storage_path' => base_path('vendor/temper/seederplus/storage/snapshots/'),
    'storage_file' => base_path('vendor/temper/seederplus/storage/seederplus.json'),
    'base_snapshot' => base_path('vendor/temper/seederplus/storage/base-snapshot.sql'),

    'relation_finders' => [
        \Temper\SeederPlus\ModelFinders\FindById::class => 'Find by id'
    ]
];
