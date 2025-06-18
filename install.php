<?php
// install.php
require 'db_config.php';

try {
    // Crear tablas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            catalogo VARCHAR(20) NOT NULL
        );
        
        CREATE TABLE IF NOT EXISTS productos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            precio DECIMAL(10,2) NOT NULL,
            descripcion TEXT,
            imagen VARCHAR(255),
            catalogo VARCHAR(20) NOT NULL
        );
    ");

    // Insertar usuarios
    $stmt = $pdo->prepare("INSERT IGNORE INTO usuarios (username, password, catalogo) VALUES (?, ?, ?)");
    $usuarios = [
        ['admin', password_hash('1234', PASSWORD_DEFAULT), 'admin'],
        ['cliente1', password_hash('1234', PASSWORD_DEFAULT), 'cliente1'],
        ['cliente2', password_hash('1234', PASSWORD_DEFAULT), 'cliente2']
    ];
    
    foreach ($usuarios as $usuario) {
        $stmt->execute($usuario);
    }

    // Insertar productos
    $stmt = $pdo->prepare("INSERT IGNORE INTO productos (nombre, precio, descripcion, imagen, catalogo) VALUES (?, ?, ?, ?, ?)");
    $productos = [
        ['Producto Premium A', 499.99, 'Producto de gama alta con todas las características.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
        ['Accesorio Exclusivo B', 99.50, 'Accesorio solo disponible para usuarios premium.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
        ['Producto Estándar A', 299.99, 'Versión estándar del producto con características básicas.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
        ['Accesorio Básico B', 49.50, 'Accesorio funcional para uso diario.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
        ['Producto Básico A', 199.99, 'Versión económica con funciones esenciales.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2']
    ];
    
    foreach ($productos as $producto) {
        $stmt->execute($producto);
    }

    echo "Base de datos y datos iniciales creados correctamente!";
    
} catch (PDOException $e) {
    die("Error al crear la base de datos: " . $e->getMessage());
}