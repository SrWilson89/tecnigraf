<?php
session_start();
require 'db_config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($username) && !empty($password)) {
    try {
        // Buscar usuario en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            // Verificar la contraseña hasheada
            if (password_verify($password, $usuario['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $usuario['username'];
                $_SESSION['catalogo'] = $usuario['catalogo'];
                header('Location: index.php');
                exit;
            } else {
                // Contraseña incorrecta
                error_log("Intento fallido para usuario: $username - Contraseña incorrecta");
            }
        } else {
            // Usuario no existe
            error_log("Intento de login para usuario no existente: $username");
        }
    } catch (PDOException $e) {
        error_log("Error de base de datos: " . $e->getMessage());
    }
}

// Redirigir con error
header('Location: login.php?error=1');
exit;
?>