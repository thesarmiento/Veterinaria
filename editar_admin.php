<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_mail'])) {
    header('Location: login.php');
    exit();
}

$mensaje = "";
if (!isset($_GET['cedula'])) {
    header('Location: admin.php');
    exit();
}

$cedula = $_GET['cedula'];


$sql = "SELECT * FROM administradores WHERE cedula='$cedula'";
$res = $conn->query($sql);
if (!$res || $res->num_rows == 0) {
    die("Administrador no encontrado");
}
$admin = $res->fetch_assoc();


if (isset($_POST['editar'])) {
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $mail = $_POST['mail'];
    $password = !empty($_POST['password']) ? md5($_POST['password']) : null;

  
    if ($admin['mail'] === 'admin@admin.com' && $mail !== 'admin@admin.com') {
        $mensaje = "No se puede cambiar el mail del admin principal";
    } else {
        $sql_up = "UPDATE administradores SET 
            primer_nombre='$primer_nombre', 
            segundo_nombre='$segundo_nombre',
            primer_apellido='$primer_apellido',
            segundo_apellido='$segundo_apellido',
            mail='$mail'";

        if ($password) {
            $sql_up .= ", password='$password'";
        }

        $sql_up .= " WHERE cedula='$cedula'";

        if ($conn->query($sql_up)) {
            $mensaje = "Administrador actualizado correctamente.";
            header('Location: admin.php'); 
            exit();
        } else {
            $mensaje = "Error al actualizar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Administrador</title>
</head>
<body>
    
<h2>Editar Administrador</h2>
<p style="color:green;"><?php echo $mensaje; ?></p>

<form method="POST">
    <label>Primer Nombre:</label><br>
    <input type="text" name="primer_nombre" value="<?php echo $admin['primer_nombre']; ?>" required><br>
    <label>Segundo Nombre:</label><br>
    <input type="text" name="segundo_nombre" value="<?php echo $admin['segundo_nombre']; ?>"><br>
    <label>Primer Apellido:</label><br>
    <input type="text" name="primer_apellido" value="<?php echo $admin['primer_apellido']; ?>" required><br>
    <label>Segundo Apellido:</label><br>
    <input type="text" name="segundo_apellido" value="<?php echo $admin['segundo_apellido']; ?>"><br>
    <label>Email:</label><br>
    <input type="email" name="mail" value="<?php echo $admin['mail']; ?>" required><br>
    <label>Contraseña (dejar vacío para no cambiar):</label><br>
    <input type="password" name="password"><br><br>
    <button type="submit" name="editar">Actualizar</button>
</form>

<p><a href="admin.php">Volver al panel principal</a></p>
</body>
</html>
