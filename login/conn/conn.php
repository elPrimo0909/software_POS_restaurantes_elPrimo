<?php 

// Use environment variables when available (for Docker or hosted environments)
$servername = getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost';
$username = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$db = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'datos';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log and show minimal message to avoid leaking credentials
    error_log('DB connection failed: ' . $e->getMessage());
    echo "Failed to connect to database.";
    exit;
}

?>