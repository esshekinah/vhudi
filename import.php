<?php
/**
 * One-time database import script.
 * IMPORTANT: Delete this file immediately after use.
 */

// Simple auth token — must match IMPORT_TOKEN env var on Render
$token = getenv('IMPORT_TOKEN');
if (!$token || ($_GET['token'] ?? '') !== $token) {
    http_response_code(403);
    die('Forbidden. Provide ?token=YOUR_IMPORT_TOKEN');
}

// Disable fatal errors from mysqli so we can handle them gracefully
mysqli_report(MYSQLI_REPORT_OFF);

require __DIR__ . '/configurations/conn.php';

if (mysqli_connect_errno()) {
    die('DB connection failed: ' . mysqli_connect_error());
}

// Disable Aiven's strict primary key requirement for this session
mysqli_query($conn, "SET SESSION sql_require_primary_key = 0");
// Also disable foreign key checks during import to avoid ordering issues
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");
mysqli_query($conn, "SET UNIQUE_CHECKS = 0");

$sql_file = __DIR__ . '/vhudi.sql';
if (!file_exists($sql_file)) {
    die('vhudi.sql not found.');
}

$sql = file_get_contents($sql_file);

// Strip comments and split into statements
$sql = preg_replace('/^--.*$/m', '', $sql);
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    fn($s) => strlen($s) > 0
);

$success = 0;
$errors  = [];

foreach ($statements as $statement) {
    if (!mysqli_query($conn, $statement)) {
        $errors[] = '<b>' . htmlspecialchars(mysqli_error($conn)) . '</b><br>'
                  . '<small><code>' . htmlspecialchars(substr($statement, 0, 200)) . '</code></small>';
    } else {
        $success++;
    }
}

// Re-enable checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
mysqli_query($conn, "SET UNIQUE_CHECKS = 1");

mysqli_close($conn);

echo "<!DOCTYPE html><html><body style='font-family:sans-serif;padding:20px'>";
echo "<h2>Import complete</h2>";
echo "<p><strong>✅ Statements executed:</strong> $success</p>";

if ($errors) {
    echo "<p><strong>⚠️ Errors (" . count($errors) . "):</strong></p><ul style='line-height:2'>";
    foreach ($errors as $e) {
        echo "<li>$e</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No errors. 🎉 Delete import.php from the repo now.</p>";
}
echo "</body></html>";
