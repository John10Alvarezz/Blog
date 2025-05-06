<?php
/**
 * Página de inicio de sesión
 * Permite a los usuarios autenticarse en el sistema
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

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validación básica
    if (empty($username) || empty($password)) {
        $error = 'Por favor, introduce tu nombre de usuario y contraseña.';
    } else {
        // Intentar autenticar al usuario
        $user = loginUser($username, $password);

        if ($user) {
            // Iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirigir al panel de administración o a la página de inicio
            header('Location: admin/dashboard.php');
            exit;
        } else {
            $error = 'Nombre de usuario o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Blog CMS</title>
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
        <h1 class="mb-4">Iniciar sesión</h1>

        <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </div>
        </form>

        <div class="mt-3">
            ¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>
        </div>
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