<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Try with different configurations
    $host = 'localhost';
    $port = '3306';
    $dbname = 'misc';
    $username = 'fred';
    $password = 'zap';
    
    // Try connecting without port first
    $dsn = "mysql:host=$host;dbname=$dbname";
    
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connected successfully!";
    
} catch (PDOException $e) {
    // Try alternative configurations
    echo "<h3>Database Connection Error</h3>";
    echo "<p><strong>Error:</strong> " . htmlentities($e->getMessage()) . "</p>";
    
    echo "<h4>Troubleshooting steps:</h4>";
    echo "<ol>";
    echo "<li>Make sure MySQL is running in XAMPP</li>";
    echo "<li>Try username: 'root' with no password</li>";
    echo "<li>Check XAMPP Control Panel</li>";
    echo "</ol>";
    
    // Try connecting with root (common XAMPP default)
    try {
        echo "<p>Trying with 'root' username...</p>";
        $pdo = new PDO("mysql:host=localhost;dbname=misc", 'root', '');
        echo "<p style='color:green'>Connected with 'root' user!</p>";
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e2) {
        echo "<p style='color:red'>Failed with root: " . htmlentities($e2->getMessage()) . "</p>";
        die("Database connection failed. Please check XAMPP.");
    }
}
?>