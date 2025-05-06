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
$post_id = '';
$title = '';
$content = '';
$errors = [];
$success_msg = '';

// Verificar si se ha proporcionado un ID de post
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $post_id = $_GET['id'];

    // Obtener los datos del post
    $conn = getDbConnection();
    $post = getPostById($conn, $post_id);

    // Verificar si el post existe
    if ($post) {
        $title = $post['title'];
        $content = $post['content'];
    } else {
        // Si el post no existe, redirigir a la lista de posts
        header("Location: manage_posts.php");
        exit();
    }
} else {
    // Si no se proporciona ID, redirigir a la lista de posts
    header("Location: manage_posts.php");
    exit();
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar el título
    if (empty($_POST['title'])) {
        $errors[] = "El título es obligatorio";
    } else {
        $title = trim($_POST['title']);
    }

    // Validar el contenido
    if (empty($_POST['content'])) {
        $errors[] = "El contenido es obligatorio";
    } else {
        $content = trim($_POST['content']);
    }

    // Si no hay errores, actualizar el post
    if (empty($errors)) {
        $conn = getDbConnection();

        // Intentar actualizar el post
        if (updatePost($conn, $post_id, $title, $content)) {
            $success_msg = "Post actualizado correctamente";

            // Actualizar las variables locales con los nuevos datos
            $post = getPostById($conn, $post_id);
            $title = $post['title'];
            $content = $post['content'];
        } else {
            $errors[] = "Error al actualizar el post";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Post - Blog CMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Panel de Administración - Editar Post</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="manage_posts.php">Gestionar Posts</a></li>
                    <li><a href="create_post.php">Crear Post</a></li>
                    <li><a href="../logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="edit-post">
                <h2>Editar Post</h2>

                <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if (!empty($success_msg)): ?>
                <div class="success-message">
                    <?php echo $success_msg; ?>
                </div>
                <?php endif; ?>

                <form action="edit_post.php?id=<?php echo $post_id; ?>" method="post">
                    <div class="form-group">
                        <label for="title">Título</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="content">Contenido</label>
                        <textarea id="content" name="content" rows="10"
                            required><?php echo htmlspecialchars($content); ?></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn">Actualizar Post</button>
                        <a href="manage_posts.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Blog de John Álvarez. Todos los derechos reservados.</p>
        </footer>
    </div>

    <script src="../assets/js/script.js"></script>
</body>

</html>