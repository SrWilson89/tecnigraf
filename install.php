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
    // 20 productos para admin
    ['Laptop Elite Pro', 1999.99, 'Laptop empresarial con procesador i9 y 32GB RAM.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Smartphone Infinity X', 1299.50, 'Teléfono insignia con cámara de 108MP y pantalla AMOLED.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Monitor 4K Curvo', 899.00, 'Monitor curvo de 32" con resolución 4K y 144Hz.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Teclado Mecánico Premium', 199.99, 'Teclado mecánico con switches Cherry MX y retroiluminación RGB.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Mouse Ergonómico Pro', 149.50, 'Mouse ergonómico con seguimiento de 16000DPI.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Auriculares Noise Cancelling', 349.99, 'Auriculares con cancelación de ruido activa y sonido surround.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Disco SSD 2TB NVMe', 299.00, 'Disco SSD de alta velocidad para profesionales.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Tarjeta Gráfica RTX 4090', 1599.99, 'Tarjeta gráfica para gaming y renderizado profesional.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Router WiFi 6', 249.50, 'Router de última generación con cobertura para toda la casa.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Impresora 3D Industrial', 2499.00, 'Impresora 3D para prototipado profesional.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Cámara Profesional 8K', 3499.99, 'Cámara para videografía profesional con grabación 8K.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Micrófono Estudio', 499.50, 'Micrófono de condensador para grabaciones profesionales.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Tablet Pro Dibujo', 899.00, 'Tablet con pantalla táctil y lápiz para artistas digitales.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Workstation Empresarial', 4999.99, 'Estación de trabajo para diseño y renderizado.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['NAS 8 Bay', 1299.50, 'Sistema de almacenamiento en red para empresas.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Proyector 4K Laser', 2999.00, 'Proyector láser para salas de conferencias.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Escáner Documentos', 599.99, 'Escáner de alta velocidad para oficinas.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Servidor Rack 1U', 1999.50, 'Servidor empresarial para centros de datos.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['Switch 48 Puertos', 899.00, 'Switch gestionable para redes empresariales.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    ['UPS Industrial', 1499.99, 'Sistema de alimentación ininterrumpida para servidores.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'admin'],
    
    // 15 productos para cliente1
    ['Laptop Business', 999.99, 'Laptop para profesionales con buen rendimiento.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Smartphone Advanced', 699.50, 'Teléfono con buen equilibrio entre precio y rendimiento.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Monitor Full HD', 199.00, 'Monitor de 24" con resolución Full HD.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Teclado Semi-Mecánico', 79.99, 'Teclado con buen tacto para uso diario.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Mouse Inalámbrico', 49.50, 'Mouse cómodo para oficina.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Auriculares Bluetooth', 99.99, 'Auriculares inalámbricos con buena calidad de sonido.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Disco SSD 1TB', 129.00, 'Disco SSD para mejorar el rendimiento de tu PC.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Tarjeta Gráfica RTX 3060', 399.99, 'Buena tarjeta gráfica para gaming.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Router WiFi 5', 99.50, 'Router para hogares pequeños.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Impresora Multifunción', 199.00, 'Impresora para el hogar y pequeñas oficinas.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Cámara Semi-Profesional', 599.99, 'Cámara para aficionados serios.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Micrófono USB', 99.50, 'Micrófono para streamers principiantes.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Tablet Estándar', 299.00, 'Tablet para consumo multimedia.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['PC Todo-en-Uno', 799.99, 'Computadora todo-en-uno para el hogar.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    ['Disco Duro Externo 2TB', 89.50, 'Almacenamiento portátil para respaldos.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente1'],
    
    // 10 productos para cliente2
    ['Laptop Básica', 499.99, 'Laptop para tareas cotidianas.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2'],
    ['Smartphone Económico', 299.50, 'Teléfono para uso básico.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2'],
    ['Monitor 20" HD', 129.00, 'Monitor económico para oficina.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2'],
    ['Teclado Membrana', 29.99, 'Teclado básico resistente.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2'],
    ['Mouse Básico', 19.50, 'Mouse simple y funcional.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2'],
    ['Auriculares con Cable', 29.99, 'Auriculares económicos.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2'],
    ['Disco Duro 1TB', 49.00, 'Almacenamiento económico.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2'],
    ['Tarjeta Gráfica GTX 1650', 199.99, 'Tarjeta gráfica para gaming casual.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2'],
    ['Router Básico', 49.50, 'Router para conexiones simples.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2'],
    ['Impresora Básica', 99.00, 'Impresora monocromática económica.', 'https://tecnigraf.es/netoffice2/servidorFicheros/4/pics/20250508112654_agrupProd1269_cuadr%C3%ADptico.jpg', 'cliente2']
];
    
    foreach ($productos as $producto) {
        $stmt->execute($producto);
    }

    echo "Base de datos y datos iniciales creados correctamente!";
    
} catch (PDOException $e) {
    die("Error al crear la base de datos: " . $e->getMessage());
}