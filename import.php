<?php
/**
 * One-time database import script.
 * IMPORTANT: Delete this file immediately after use.
 * Access it once at: https://your-render-url/import.php
 */

// Simple auth token — must match IMPORT_TOKEN env var on Render
$token = getenv('IMPORT_TOKEN');
if (!$token || ($_GET['token'] ?? '') !== $token) {
    http_response_code(403);
    die('Forbidden. Provide ?token=YOUR_IMPORT_TOKEN');
}

require __DIR__ . '/configurations/conn.php';

if (mysqli_connect_errno()) {
    die('DB connection failed: ' . mysqli_connect_error());
}

$sql_file = __DIR__ . '/vhudi.sql';
if (!file_exists($sql_file)) {
    die('vhudi.sql not found.');
}

$sql = file_get_contents($sql_file);

// Split into individual statements (handles delimiters properly)
// Remove comments and split on semicolons
$sql = preg_replace('/^--.*$/m', '', $sql);          // strip -- comments
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql);      // strip /* */ comments
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    fn($s) => strlen($s) > 0
);

$success = 0;
$errors  = [];

foreach ($statements as $statement) {
    if (!mysqli_query($conn, $statement)) {
        $errors[] = htmlspecialchars(mysqli_error($conn)) . '<br><small>' . htmlspecialchars(substr($statement, 0, 120)) . '...</small>';
    } else {
        $success++;
    }
}

echo "<h2>Import complete</h2>";
echo "<p><strong>✅ Statements executed:</strong> $success</p>";

if ($errors) {
    echo "<p><strong>⚠️ Errors (" . count($errors) . "):</strong></p><ul>";
    foreach ($errors as $e) {
        echo "<li>$e</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No errors. 🎉 You can now delete import.php from the repo.</p>";
}

mysqli_close($conn);
