<?php
/**
 * Página de registro de usuarios
 * Permite a los visitantes crear una cuenta nueva
 */

// Iniciar sesión
session_start();

// Si ya está logueado, redirigir al inicio
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Incluir archivos necesarios
require_once 'config/db.php';
require_once 'includes/user_functions.php';

$error = '';
$success = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validaciones básicas (adicionales a las del JavaScript)
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        // Intentar registrar al usuario
        $result = registerUser($username, $email, $password);

        if ($result === true) {
            $success = 'Cuenta creada correctamente. Ahora puedes iniciar sesión.';
        } else {
            $error = $result; // El mensaje de error devuelto por la función
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Blog CMS</title>
    <!-- Enlace a la hoja de estilos -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Header / Barra de navegación -->
    <header class="navbar">
        <a href="index.php" class="navbar-brand">Blog CMS</a>
        <ul class="navbar-nav">
            <li class="nav-item"><a href="index.php" class="nav-link">Inicio</a></li>
            <li class="nav-item"><a href="login.php" class="nav-link">Iniciar sesión</a></li>
            <li class="nav-item"><a href="register.php" class="nav-link">Registrarse</a></li>
        </ul>
    </header>

    <div class="container">
        <h1 class="mb-4">Crear una cuenta</h1>

        <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
            <div class="mt-3">
                <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
            </div>
        </div>
        <?php else: ?>
        <form id="registerForm" method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>

            <div class=" form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>

            <div class=" form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirmar contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>
        </form>

        <div class="mt-3">
            ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Blog de John Álvarez. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Script JavaScript -->
    <script src="assets/js/script.js"></script>
</body>

</html>