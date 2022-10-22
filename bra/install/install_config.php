<?php
return [
    "routes" => [
        "web" => [
            "install" => [],
        ]
    ],

    "cors" => [
        "paths" => [
            'install/index/*'
        ]
    ],
    "extra_files" => [
//        __DIR__ . DS . "function.php"
    ]
];