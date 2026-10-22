<?php

// Read credentials from environment variables with fallbacks for local development
$server_name    = getenv('DB_HOST')     ?: 'vhudiDB';
$db_name        = getenv('DB_DATABASE') ?: 'vhudi';
$mysql_username = getenv('DB_USERNAME') ?: 'user';
$mysql_password = getenv('DB_PASSWORD') ?: '2022';
$db_port        = (int)(getenv('DB_PORT') ?: 3306);

$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name, $db_port);

// PlanetScale (and most cloud MySQL providers) require SSL
if ($conn && getenv('DB_SSL') === 'true') {
    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
}

if ($conn) {
    ini_set('memory_limit', '-1');
} else {
    echo "Database connection fail: " . mysqli_connect_error();
}

?>