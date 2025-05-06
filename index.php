<?php
/**
 * Página principal del Blog CMS
 * Muestra los posts más recientes
 */

// Iniciar sesión
session_start();

// Incluir archivos necesarios
require_once 'config/db.php';
require_once 'includes/user_functions.php';
require_once 'includes/post_functions.php';

// Obtener los 5 posts más recientes
$posts = getPosts(5);

// Obtener usuario actual (si está logueado)
$currentUser = isLoggedIn() ? getCurrentUser() : null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog CMS - Inicio</title>
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
        <h1 class="mb-4">Últimos artículos</h1>

        <?php if (empty($posts)): ?>
            <div class="alert alert-secondary">
                No hay posts disponibles en este momento.
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">
                            <a href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                        </h2>
                        <div class="post-meta">
                            Por <?php echo htmlspecialchars($post['username'] ?? 'Usuario desconocido'); ?> •
                            <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>
                        </div>
                        <p class="card-text">
                            <?php
                            // Mostrar un resumen del contenido
                            $content = strip_tags($post['content']);
                            echo substr($content, 0, 200) . (strlen($content) > 200 ? '...' : '');
                            ?>
                        </p>
                        <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Leer más</a>
                    </div>
                </div>
            <?php endforeach; ?>
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