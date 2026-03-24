<?php
include_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

if ($db) {
    echo "Database connected successfully.";
} else {
    echo "Database connection failed.";
}
?>