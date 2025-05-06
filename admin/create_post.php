<?php
/**
 * Página de creación de posts
 * Permite al usuario crear un nuevo post
 */

// Iniciar sesión
session_start();

// Incluir archivos necesarios
require_once '../config/db.php';
require_once '../includes/user_functions.php';
require_once '../includes/post_functions.php';

// Verificar que el usuario esté autenticado
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$error = '';
$success = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    // Validación básica
    if (empty($title)) {
        $error = 'Por favor, ingresa un título para el post.';
    } elseif (empty($content)) {
        $error = 'Por favor, ingresa el contenido del post.';
    } else {
        // Intentar crear el post
        $result = createPost($title, $content, $_SESSION['user_id']);

        if ($result === true) {
            $success = 'Post creado correctamente.';
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
    <title>Crear Nuevo Post - Blog CMS</title>
    <!-- Enlace a la hoja de estilos -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Header / Barra de navegación -->
    <header class="navbar">
        <a href="../index.php" class="navbar-brand">Blog CMS</a>
        <ul class="navbar-nav">
            <li class="nav-item"><a href="../index.php" class="nav-link">Inicio</a></li>
            <li class="nav-item"><a href="dashboard.php" class="nav-link">Panel</a></li>
            <li class="nav-item"><a href="../logout.php" class="nav-link">Cerrar sesión</a></li>
        </ul>
    </header>

    <div class="container">
        <h1 class="mb-4">Crear Nuevo Post</h1>

        <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
            <div class="mt-3">
                <a href="dashboard.php" class="btn btn-primary">Volver al panel</a>
            </div>
        </div>
        <?php else: ?>
        <form id="postForm" method="POST" action="">
            <div class="form-group">
                <label for="title" class="form-label">Título</label>
                <input type="text" id="title" name="title" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Contenido</label>
                <textarea id="content" name="content" class="form-control"
                    required><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Publicar</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Blog de John Álvarez. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Script JavaScript -->
    <script src="../assets/js/script.js"></script>
</body>

</html>