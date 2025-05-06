<?php
/**
 * Archivo de configuración para la conexión a la base de datos
 * Este archivo establece la conexión con MySQL utilizando PDO
 */

// Parámetros de conexión a la base de datos
$host = 'localhost';      // Host de la base de datos
$dbname = 'blog_cms';     // Nombre de la base de datos
$username = 'root';       // Usuario de MySQL (cambia según tu configuración)
$password = '';           // Contraseña (cambia según tu configuración)

try {
    // Crear conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Configurar PDO para lanzar excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configurar el modo de búsqueda por defecto
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // En caso de error, mostrar mensaje y terminar la ejecución
    die("Error de conexión: " . $e->getMessage());
}