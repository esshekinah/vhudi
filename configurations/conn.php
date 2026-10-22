<?php

// Read credentials from environment variables
// No fallback to hardcoded values — fail clearly if env vars are missing in production
$server_name    = getenv('DB_HOST');
$db_name        = getenv('DB_DATABASE');
$mysql_username = getenv('DB_USERNAME');
$mysql_password = getenv('DB_PASSWORD');
$db_port        = (int)(getenv('DB_PORT') ?: 3306);

// Fall back to local Docker credentials only in non-production
if (getenv('ENVIRONMENT') !== 'production') {
    $server_name    = $server_name    ?: 'vhudiDB';
    $db_name        = $db_name        ?: 'vhudi';
    $mysql_username = $mysql_username ?: 'user';
    $mysql_password = $mysql_password ?: '2022';
}

if (!$server_name || !$db_name || !$mysql_username) {
    die('Database configuration missing. Please set DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD environment variables.');
}

$ca_cert = __DIR__ . '/aiven-ca.pem';

// mysqli_ssl_set must be called before mysqli_real_connect
$conn = mysqli_init();
if (file_exists($ca_cert)) {
    mysqli_ssl_set($conn, NULL, NULL, $ca_cert, NULL, NULL);
    mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
}

mysqli_real_connect($conn, $server_name, $mysql_username, $mysql_password, $db_name, $db_port);

if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
} else {
    ini_set('memory_limit', '-1');
}

?>
