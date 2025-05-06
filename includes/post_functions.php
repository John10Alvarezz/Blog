<?php
/**
 * Funciones relacionadas con los posts del blog
 * Incluye creación, edición, eliminación y visualización de posts
 */

// Incluir archivo de configuración de la BD si no está incluido ya
if (!function_exists('getPosts')) {
    require_once __DIR__ . '/../config/db.php';
}

/**
 * Obtiene todos los posts ordenados por fecha más reciente
 * 
 * @param int $limit Cantidad de posts a obtener (0 para todos)
 * @return array Lista de posts
 */
function getPosts($limit = 0)
{
    global $pdo;

    try {
        $sql = "SELECT p.*, u.username 
                FROM posts p 
                LEFT JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$limit]);
        } else {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }

        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Obtiene un post específico por su ID
 * 
 * @param int $id ID del post
 * @return array|null Datos del post o null si no existe
 */
function getPostById($id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT p.*, u.username 
                              FROM posts p 
                              LEFT JOIN users u ON p.user_id = u.id 
                              WHERE p.id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            return null;
        }

        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Crea un nuevo post
 * 
 * @param string $title Título del post
 * @param string $content Contenido del post
 * @param int $userId ID del usuario autor
 * @return bool|string True si la creación es exitosa, mensaje de error en caso contrario
 */
function createPost($title, $content, $userId)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $userId]);
        return true;
    } catch (PDOException $e) {
        return "Error al crear el post: " . $e->getMessage();
    }
}

/**
 * Actualiza un post existente
 * 
 * @param int $id ID del post
 * @param string $title Nuevo título
 * @param string $content Nuevo contenido
 * @param int $userId ID del usuario que realiza la actualización (para verificar permisos)
 * @return bool|string True si la actualización es exitosa, mensaje de error en caso contrario
 */
function updatePost($id, $title, $content, $userId)
{
    global $pdo;

    try {
        // Primero verificamos que el usuario sea el autor del post
        $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch();

        if (!$post) {
            return "El post no existe.";
        }

        // Solo el autor puede editar (podríamos añadir excepciones para administradores)
        if ($post['user_id'] != $userId) {
            return "No tienes permisos para editar este post.";
        }

        // Actualizar el post
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);

        return true;
    } catch (PDOException $e) {
        return "Error al actualizar el post: " . $e->getMessage();
    }
}

/**
 * Elimina un post
 * 
 * @param int $id ID del post
 * @param int $userId ID del usuario que realiza la eliminación (para verificar permisos)
 * @return bool|string True si la eliminación es exitosa, mensaje de error en caso contrario
 */
function deletePost($id, $userId)
{
    global $pdo;

    try {
        // Primero verificamos que el usuario sea el autor del post
        $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch();

        if (!$post) {
            return "El post no existe.";
        }

        // Solo el autor puede eliminar (podríamos añadir excepciones para administradores)
        if ($post['user_id'] != $userId) {
            return "No tienes permisos para eliminar este post.";
        }

        // Eliminar el post
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);

        return true;
    } catch (PDOException $e) {
        return "Error al eliminar el post: " . $e->getMessage();
    }
}

/**
 * Obtiene los posts de un usuario específico
 * 
 * @param int $userId ID del usuario
 * @return array Lista de posts del usuario
 */
function getUserPosts($userId)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}