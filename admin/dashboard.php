<?php
/**
 * Panel de administración
 * Proporciona acceso a las funciones de gestión de posts
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

// Obtener información del usuario actual
$currentUser = getCurrentUser();

// Obtener posts del usuario
$userPosts = getUserPosts($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Blog CMS</title>
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
        <div class="dashboard-header">
            <h1>Panel de Administración</h1>
            <a href="create_post.php" class="btn btn-primary">Crear nuevo post</a>
        </div>

        <div class="mb-4">
            <h2>Bienvenido, <?php echo htmlspecialchars($currentUser['username']); ?></h2>
            <p>Desde aquí puedes gestionar tus posts.</p>
        </div>

        <h3>Mis Posts</h3>

        <?php if (empty($userPosts)): ?>
        <div class="alert alert-secondary">
            No has creado ningún post todavía.
        </div>
        <?php else: ?>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha de creación</th>
                    <th>Última actualización</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userPosts as $post): ?>
                <tr>
                    <td><a
                            href="../post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($post['updated_at'])); ?></td>
                    <td>
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">Editar</a>
                        <a href="manage_posts.php?delete=<?php echo $post['id']; ?>"
                            class="btn btn-danger delete-post">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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