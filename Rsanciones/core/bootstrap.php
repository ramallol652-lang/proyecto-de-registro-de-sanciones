<?php

spl_autoload_register(function ($class) {
    $folders = [
        __DIR__,
        __DIR__ . '/../app/controllers',
        __DIR__ . '/../app/models',
        __DIR__ . '/../app/repositories',
        __DIR__ . '/../app/services',
        __DIR__ . '/../app/contracts'
    ];
    foreach ($folders as $folder) {
        $file = $folder . '/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

Auth::start();
