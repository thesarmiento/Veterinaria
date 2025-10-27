<?php
session_start();
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mail = $_POST['mail'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM administradores WHERE mail='$mail' AND password='$password'";
    $result = $conn->query($sql);

    
    if (!$result) {
        die("Error en la consulta SQL: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin_mail'] = $admin['mail'];
        $_SESSION['cedula'] = $admin['cedula'];
        header('Location: admin.php');
        exit();
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Administrador</title>
</head>
<body>
<h2>Login Administrador</h2>
<form method="POST">
    <label>Mail:</label><br>
    <input type="email" name="mail" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Ingresar</button>
</form>

<?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
</body>
</html>
