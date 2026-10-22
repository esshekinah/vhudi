<?php

// TiDB Cloud connection (MySQL-compatible)
$server_name    = 'gateway01.eu-central-1.prod.aws.tidbcloud.com';
$db_name        = 'vhudi';
$mysql_username = '4EYWK1MFUyxNbX3.root';
$mysql_password = 'd9m58ZUzRnLgubxM';
$db_port        = 4000;

$ca_cert = __DIR__ . '/isrgrootx1.pem';

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
