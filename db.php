<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'admin_app';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
