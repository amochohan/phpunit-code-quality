<?php

return [

    'php_mess_detector_path' => '/home/vagrant/Code/project/vendor/bin/',

    'project_root' => __DIR__,

    'scan_directories' => [
        'app',
    ],

    'excluded_directories' => [
        'app/Support',
    ],

    'excluded_files' => [
        'app/Http/Controllers/SomeController.php',
    ],

];