<?php
// db_config.php
$host = 'localhost'; // Servidor MySQL (generalmente localhost)
$db   = 'tecnigraf_catalogos'; // Nombre de tu base de datos
$user = 'root'; // Usuario de MySQL (root es común en XAMPP)
$pass = ''; // Contraseña (vacía por defecto en XAMPP)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Muestra el error real para diagnóstico (eliminar en producción)
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>