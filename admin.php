<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_mail'])) {
    header('Location: login.php');
    exit();
}

$cedula_sesion = $_SESSION['cedula'];
$mensaje = "";


if (isset($_POST['crear'])) {
    $cedula = $_POST['cedula'];
    $primer_nombre = $_POST['primer_nombre'];
    $segundo_nombre = $_POST['segundo_nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $mail = $_POST['mail'];
    $password = md5($_POST['password']);

    if ($mail === 'admin@admin.com') {
        $mensaje = "No se puede crear otro admin principal";
    } else {
        $sql = "INSERT INTO administradores 
            (cedula, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, mail, password, creado_por)
            VALUES ('$cedula','$primer_nombre','$segundo_nombre','$primer_apellido','$segundo_apellido','$mail','$password','$cedula_sesion')";
        if ($conn->query($sql)) {
            $mensaje = "Administrador creado correctamente.";
        } else {
            $mensaje = "Error: " . $conn->error;
        }
    }
}


if (isset($_GET['eliminar'])) {
    $cedula_eliminar = $_GET['eliminar'];

    
    $sql_check = "SELECT mail FROM administradores WHERE cedula='$cedula_eliminar'";
    $res_check = $conn->query($sql_check);
    $row_check = $res_check->fetch_assoc();

    if ($row_check['mail'] === 'admin@admin.com') {
        $mensaje = "No se puede eliminar el admin principal.";
    } else {
        $sql = "DELETE FROM administradores WHERE cedula='$cedula_eliminar'";
        if ($conn->query($sql)) {
            $mensaje = "Administrador eliminado correctamente.";
        } else {
            $mensaje = "Error al eliminar: " . $conn->error;
        }
    }
}


$sql = "SELECT * FROM administradores";
$result = $conn->query($sql);
if (!$result) die("Error SQL: " . $conn->error);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel Administrador</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top:20px; }
        th, td { border:1px solid #000; padding:8px; text-align:center; }
        th { background:#eee; }
        a { text-decoration:none; margin:0 5px; }
    </style>
</head>
<body>
<h2>Bienvenido, <?php echo $_SESSION['admin_mail']; ?></h2>
<p style="color:green;"><?php echo $mensaje; ?></p>
<p><a href="logout.php" style="color:red; font-weight:bold;">Cerrar sesión</a></p>  

<h3>Crear Administrador</h3>
<form method="POST">
    <label>Primer Nombre:</label><br>
    <input type="text" name="primer_nombre" required><br>
    <label>Segundo Nombre:</label><br>
    <input type="text" name="segundo_nombre"><br>
    <label>Primer Apellido:</label><br>
    <input type="text" name="primer_apellido" required><br>
    <label>Segundo Apellido:</label><br>
    <input type="text" name="segundo_apellido"><br>
    <label>Cedula:</label><br>
    <input type="text" name="cedula" required><br>
    <label>Email:</label><br>
    <input type="email" name="mail" required><br>
    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit" name="crear">Crear</button>
</form>


<h3>Administradores existentes</h3>
<table>
    <tr>
        <th>Cedula</th>
        <th>Primer Nombre</th>
        <th>Segundo Nombre</th>
        <th>Primer Apellido</th>
        <th>Segundo Apellido</th>
        <th>Email</th>
        <th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['cedula']; ?></td>
        <td><?php echo $row['primer_nombre']; ?></td>
        <td><?php echo $row['segundo_nombre']; ?></td>
        <td><?php echo $row['primer_apellido']; ?></td>
        <td><?php echo $row['segundo_apellido']; ?></td>
        <td><?php echo $row['mail']; ?></td>
        <td>
            <?php if($row['mail'] !== 'admin@admin.com'): ?>
                <a href="editar_admin.php?cedula=<?php echo $row['cedula']; ?>">Editar</a> | 
                <a href="admin.php?eliminar=<?php echo $row['cedula']; ?>" onclick="return confirm('¿Seguro quieres eliminar este administrador?')">Eliminar</a>
            <?php else: ?>
                Principal
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
