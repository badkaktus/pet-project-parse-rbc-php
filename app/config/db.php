<?php

/*
 * Created by Nickolay Sinyukhin on 29.03.2020 22:59
 * Copyright (c) 29.03.2020 22:59. All right reserved
 *
 * Last modified 29.03.2020 22:59 
 *
 * ¯\_(ツ)_/¯
 */

return [
    'cli' => [
        'dbname' => 'rbc_parse',
        'user' => 'rbc_user',
        'password' => 'rbc_password',
        'host' => 'db',
        'driver' => 'pdo_mysql',
        'charset' => 'utf8',
        'options' => [
            1002 => "SET NAMES 'UTF8' COLLATE 'utf8_unicode_ci'"
        ]
    ],
    'app' => [
        'dbname' => 'rbc_parse',
        'user' => 'rbc_user',
        'password' => 'rbc_password',
        'host' => 'db',
        'driver' => 'pdo_mysql',
        'charset' => 'utf8',
        'options' => [
            1002 => "SET NAMES 'UTF8' COLLATE 'utf8_unicode_ci'"
        ]
    ]
];