<?php
namespace zfMaintenanceMode;
use zfMaintenanceMode\Options;
use zfMaintenanceMode\Factory;

return [
    'service_manager' => [
        'factories' => [
            Options\ModuleOptions::class => Factory\Options\ModuleOptionsFactory::class
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];