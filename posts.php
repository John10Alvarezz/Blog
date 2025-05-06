<?php
/**
 * Página de visualización de un post individual
 * Muestra el contenido completo de un post específico
 */

// Iniciar sesión
session_start();

// Incluir archivos necesarios
require_once 'config/db.php';
require_once 'includes/user_functions.php';
require_once 'includes/post_functions.php';

// Verificar que se ha proporcionado un ID de post
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Obtener el post
$post = getPostById($_GET['id']);

// Si el post no existe, redirigir al inicio
if (!$post) {
    header('Location: index.php');
    exit;
}

// Obtener usuario actual (si está logueado)
$currentUser = isLoggedIn() ? getCurrentUser() : null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Blog CMS</title>
    <!-- Enlace a la hoja de estilos -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Header / Barra de navegación -->
    <header class="navbar">
        <a href="index.php" class="navbar-brand">Blog CMS</a>
        <ul class="navbar-nav">
            <li class="nav-item"><a href="index.php" class="nav-link">Inicio</a></li>
            <?php if ($currentUser): ?>
            <li class="nav-item"><a href="admin/dashboard.php" class="nav-link">Panel</a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link">Cerrar sesión</a></li>
            <?php else: ?>
            <li class="nav-item"><a href="login.php" class="nav-link">Iniciar sesión</a></li>
            <li class="nav-item"><a href="register.php" class="nav-link">Registrarse</a></li>
            <?php endif; ?>
        </ul>
    </header>

    <div class="container">
        <article>
            <h1 class="mb-3"><?php echo htmlspecialchars($post['title']); ?></h1>

            <div class="post-meta">
                Por <?php echo htmlspecialchars($post['username'] ?? 'Usuario desconocido'); ?> •
                <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>

                <?php if ($post['created_at'] !== $post['updated_at']): ?>
                • Actualizado el <?php echo date('d/m/Y H:i', strtotime($post['updated_at'])); ?>
                <?php endif; ?>
            </div>

            <div class="post-content">
                <?php
                // Mostrar el contenido del post
                echo nl2br(htmlspecialchars($post['content']));
                ?>
            </div>

            <?php if ($currentUser && $currentUser['id'] == $post['user_id']): ?>
            <div class="mt-4">
                <a href="admin/edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">Editar</a>
                <a href="admin/manage_posts.php?delete=<?php echo $post['id']; ?>"
                    class="btn btn-danger delete-post">Eliminar</a>
            </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="index.php" class="btn btn-primary">Volver al inicio</a>
            </div>
        </article>
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