<?php
// Iniciar sesión para verificar si el usuario está autenticado
session_start();

// Incluir archivos de configuración y funciones
require_once '../config/db.php';
require_once '../includes/post_functions.php';
require_once '../includes/user_functions.php';

// Verificar si el usuario está logueado y es administrador
if (!isLoggedIn() || !isAdmin()) {
    // Redirigir al login si no está autenticado o no es admin
    header("Location: ../login.php");
    exit();
}

// Inicializar variables
$success_msg = '';
$error_msg = '';

// Procesar eliminación de post si se recibe la solicitud
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $post_id = $_GET['delete'];
    $conn = getDbConnection();

    // Intentar eliminar el post
    if (deletePost($conn, $post_id)) {
        $success_msg = "Post eliminado correctamente";
    } else {
        $error_msg = "Error al eliminar el post";
    }
}

// Obtener todos los posts para mostrarlos en la tabla
$conn = getDbConnection();
$posts = getAllPosts($conn);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Posts - Blog CMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Panel de Administración - Gestionar Posts</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="manage_posts.php" class="active">Gestionar Posts</a></li>
                    <li><a href="create_post.php">Crear Post</a></li>
                    <li><a href="../logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="manage-posts">
                <h2>Todos los Posts</h2>

                <?php if (!empty($success_msg)): ?>
                <div class="success-message">
                    <?php echo $success_msg; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                <div class="error-messages">
                    <p><?php echo $error_msg; ?></p>
                </div>
                <?php endif; ?>

                <div class="action-buttons">
                    <a href="create_post.php" class="btn">Crear Nuevo Post</a>
                </div>

                <?php if (!empty($posts)): ?>
                <table class="posts-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo $post['id']; ?></td>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></td>
                            <td class="actions">
                                <a href="../post.php?id=<?php echo $post['id']; ?>" class="btn btn-small"
                                    target="_blank">Ver</a>
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>"
                                    class="btn btn-small btn-edit">Editar</a>
                                <a href="manage_posts.php?delete=<?php echo $post['id']; ?>"
                                    class="btn btn-small btn-delete"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este post?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="no-posts">No hay posts disponibles. <a href="create_post.php">Crear el primer post</a>.</p>
                <?php endif; ?>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Blog de John Álvarez. Todos los derechos reservados.</p>
        </footer>
    </div>

    <script src="../assets/js/script.js"></script>
</body>

</html>