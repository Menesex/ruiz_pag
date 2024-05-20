<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "JJmJ@SQL"; // Asegúrate de que esta sea la contraseña correcta para tu usuario root
$dbname = "base_datos_ejpagina";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        // Create
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $celular = $_POST['celular'];
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, celular) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $celular);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        // Update
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $celular = $_POST['celular'];
        $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, correo=?, celular=? WHERE id=?");
        $stmt->bind_param("sssi", $nombre, $correo, $celular, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        // Delete
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all users
$result = $conn->query("SELECT * FROM usuarios");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>CRUD de Usuarios</h1>
        <span class="links_footer"><a href="index.html">(Volver a inicio)</a></span>
    </header>

    <div class="crud-container">
        <dv class="form-container">
            <section id="addUser">
                <h2>Agregar Usuario</h2>
                <form action="crud.php" method="post">
                    <input type="hidden" name="create" value="1">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required><br><br>
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" required><br><br>
                    <label for="celular">Celular:</label>
                    <input type="text" id="celular" name="celular" required><br><br>
                    <button type="submit">Agregar</button>
                </form>
            </section>

            <section id="editUser">
                <h2>Editar Usuario</h2>
                <form action="crud.php" method="post">
                    <input type="hidden" name="update" value="1">
                    <input type="hidden" name="id" value="<?= isset($_POST['id']) ? $_POST['id'] : '' ?>">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= isset($_POST['nombre']) ? $_POST['nombre'] : '' ?>" required><br><br>
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" value="<?= isset($_POST['correo']) ? $_POST['correo'] : '' ?>" required><br><br>
                    <label for="celular">Celular:</label>
                    <input type="tel" id="celular" name="celular" value="<?= isset($_POST['celular']) ? $_POST['celular'] : '' ?>" required><br><br>
                    <button type="submit">Actualizar</button>
                </form>
            </section>
        </dv>

        <section>
            <h2>Usuarios</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Celular</th>
                    <th>Acciones</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['correo'] ?></td>
                    <td><?= $row['celular'] ?></td>
                    <td>
                        <form action="crud.php" method="post" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="nombre" value="<?= $row['nombre'] ?>">
                            <input type="hidden" name="correo" value="<?= $row['correo'] ?>">
                            <input type="hidden" name="celular" value="<?= $row['celular'] ?>">
                            <button type="submit" name="edit" value="1">Editar</button>
                        </form>
                        <form action="crud.php" method="post" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete" value="1">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </div>

    <footer class="footer" id="contact">
        <p>&copy; By Juan David Vergara Ruiz</p>
        <span class="links_footer"><a href="index.html">(Volver a inicio)</a></span>
    </footer>
</body>
</html>
