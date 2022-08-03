<?php

return [
    "router" => [
        "default" => [
            "options" => [
                "prefix" => "/"
            ],
            "middleware" => [
                "public" => [],
                "private" => ['auth', 'role:admin'],
                "api/blog" => ['auth', 'role:admin'],
                "api/pages" => ['auth', 'role:admin'],
                "api/admin" => ['auth', 'role:admin'],
                "admin" => ['auth', 'role:admin'],
            ]
        ]
    ]
];