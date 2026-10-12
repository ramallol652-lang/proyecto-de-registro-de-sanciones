<?php

return [
    "host" => getenv('DB_HOST') ?: "localhost",
    "port" => getenv('DB_PORT') ?: "3306",
    "dbname" => getenv('DB_NAME') ?: "sanciones",
    "user" => getenv('DB_USER') ?: "root",
    "password" => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : "",
    "charset" => "utf8mb4"
];
