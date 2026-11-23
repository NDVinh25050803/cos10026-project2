<?php
// Opens $conn to the database. Author: Nguyen Dang Vinh.
require_once 'settings.php';

try {
    $conn = mysqli_connect($host, $user, $pwd, $sql_db);
    mysqli_set_charset($conn, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Log the real error, show the visitor a plain message.
    error_log('DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('<p>Sorry, we can\'t reach the database right now. Please try again later.</p>');
}
