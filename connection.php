

<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "demo";

// Create connection
$connection = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4 for proper encoding
mysqli_set_charset($connection, "utf8mb4");

// Function to close the database connection
function closeConnection() {
    global $connection;
    if ($connection) {
        mysqli_close($connection);
    }
}

// Register shutdown function to close connection
register_shutdown_function('closeConnection');
?>