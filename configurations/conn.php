<?php

// Read credentials from environment variables with fallbacks for local development
$server_name    = getenv('DB_HOST')     ?: 'vhudiDB';
$db_name        = getenv('DB_DATABASE') ?: 'vhudi';
$mysql_username = getenv('DB_USERNAME') ?: 'user';
$mysql_password = getenv('DB_PASSWORD') ?: '2022';
$db_port        = (int)(getenv('DB_PORT') ?: 3306);

$ca_cert = __DIR__ . '/aiven-ca.pem';

// mysqli_ssl_set must be called before mysqli_real_connect when using SSL
$conn = mysqli_init();
if (file_exists($ca_cert)) {
    // Verify server identity using Aiven's CA certificate
    mysqli_ssl_set($conn, NULL, NULL, $ca_cert, NULL, NULL);
    mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
}
mysqli_real_connect($conn, $server_name, $mysql_username, $mysql_password, $db_name, $db_port);

if (mysqli_connect_errno()) {
    echo "Database connection fail: " . mysqli_connect_error();
} else {
    ini_set('memory_limit', '-1');
}

?>
