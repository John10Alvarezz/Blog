<?php
/**
 * Funciones relacionadas con los usuarios
 * Incluye registro, autenticación y gestión de sesiones
 */

// Incluir archivo de configuración de la BD si no está incluido ya
if (!function_exists('registerUser')) {
    require_once __DIR__ . '/../config/db.php';
}

/**
 * Registra un nuevo usuario en el sistema
 * 
 * @param string $username Nombre de usuario
 * @param string $email Correo electrónico
 * @param string $password Contraseña sin cifrar
 * @return bool|string True si el registro es exitoso, mensaje de error en caso contrario
 */
function registerUser($username, $email, $password)
{
    global $pdo;

    try {
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->rowCount() > 0) {
            return "El nombre de usuario o correo electrónico ya está en uso.";
        }

        // Cifrar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);

        return true;
    } catch (PDOException $e) {
        return "Error al registrar: " . $e->getMessage();
    }
}

/**
 * Autentica a un usuario
 * 
 * @param string $username Nombre de usuario
 * @param string $password Contraseña sin cifrar
 * @return bool|array Datos del usuario si la autenticación es exitosa, false en caso contrario
 */
function loginUser($username, $password)
{
    global $pdo;

    try {
        // Buscar al usuario por nombre de usuario
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() === 0) {
            return false;
        }

        $user = $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            // Eliminar la contraseña del array antes de devolverlo
            unset($user['password']);
            return $user;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Verifica si el usuario está autenticado
 * 
 * @return bool True si el usuario está autenticado, false en caso contrario
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Obtiene los datos del usuario actual
 * 
 * @return array|null Datos del usuario o null si no está autenticado
 */
function getCurrentUser()
{
    global $pdo;

    if (!isLoggedIn()) {
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Cierra la sesión del usuario actual
 */
function logoutUser()
{
    // Eliminar todas las variables de sesión
    $_SESSION = array();

    // Si se está usando un cookie de sesión, eliminarlo
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destruir la sesión
    session_destroy();
}