	<?php
// Enable full error reporting (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
define("DB_HOST", "172.20.61.47");
define("DB_PORT", "5432");
define("DB_NAME", "wbprd");
define("DB_USER", "wbprd");
define("DB_PASS", "Mn#t9*A6");

// Build connection string
$conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASS;

// Connect to PostgreSQL
$connect = pg_connect($conn_string);

// Check connection
if (!$connect) {
    echo "❌ Connection failed.\n";
    // Safely show error (only in dev, not in production)
    if (function_exists('pg_last_error')) {
        echo "Error detail: " . pg_last_error();
    }
    exit;
} else {
    echo "✅ Connection successful!\n";

    // Optional test query
    $result = pg_query($connect, "SELECT version()");
    if ($result) {
        $row = pg_fetch_row($result);
        echo "🔍 PostgreSQL Version: " . $row[0] . "\n";
        pg_free_result($result);
    }

    // Close connection
    pg_close($connect);
}
?>
    
