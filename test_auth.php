<?php
require 'db_config.php';

// Verificar hash de contraseñas
$test_passwords = [
    '1234' => password_hash('1234', PASSWORD_DEFAULT),
    'cliente123' => password_hash('cliente123', PASSWORD_DEFAULT),
    'cliente456' => password_hash('cliente456', PASSWORD_DEFAULT)
];

foreach ($test_passwords as $plain => $hash) {
    echo "Contraseña: $plain - Hash: $hash - Verificación: " . 
         (password_verify($plain, $hash) ? 'OK' : 'Fallo') . "<br>";
}

// Verificar usuarios en BD
$stmt = $pdo->query("SELECT username, password FROM usuarios");
$users = $stmt->fetchAll();

echo "<h2>Usuarios en base de datos</h2>";
foreach ($users as $user) {
    echo "Usuario: {$user['username']} - Hash: {$user['password']}<br>";
}
?>