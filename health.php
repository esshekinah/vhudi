<?php
/**
 * Health check endpoint.
 * Returns 200 OK with JSON status when everything is healthy.
 * Returns 503 if the database is unreachable.
 */

mysqli_report(MYSQLI_REPORT_OFF);

require __DIR__ . '/configurations/conn.php';

header('Content-Type: application/json');

if (mysqli_connect_errno()) {
    http_response_code(503);
    echo json_encode([
        'status'   => 'error',
        'service'  => 'vhudi',
        'db'       => 'unreachable',
        'error'    => mysqli_connect_error(),
        'time'     => date('Y-m-d H:i:s'),
    ]);
    exit;
}

mysqli_close($conn);

http_response_code(200);
echo json_encode([
    'status'  => 'ok',
    'service' => 'vhudi',
    'db'      => 'connected',
    'time'    => date('Y-m-d H:i:s'),
]);
