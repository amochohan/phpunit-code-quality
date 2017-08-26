<?php

return [

    'php_mess_detector_path' => '/home/vagrant/Code/drawmyattention/code-quality/vendor/bin/',

    'project_root' => '/home/vagrant/Code/drawmyattention/code-quality/',

    'scan_directories' => [
        'src',
    ],

    'excluded_directories' => [
        'app/Support',
        'tests',
    ],

    'excluded_files' => [
        'app/Http/Controllers/SomeController.php',
    ],

];