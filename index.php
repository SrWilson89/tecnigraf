<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

// Obtener catálogo del usuario
$catalogoUsuario = $_SESSION['catalogo'] ?? 'basico';

// Obtener productos según el catálogo del usuario
try {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE catalogo = ?");
    $stmt->execute([$catalogoUsuario]);
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    $productos = [];
    error_log("Error al obtener productos: " . $e->getMessage());
}

// Para el admin, mostrar todos los productos
if ($catalogoUsuario === 'admin') {
    try {
        $stmt = $pdo->query("SELECT * FROM productos");
        $productos = $stmt->fetchAll();
    } catch (PDOException $e) {
        $productos = [];
        error_log("Error al obtener productos para admin: " . $e->getMessage());
    }
}

// Función para generar la URL base
function base_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos - Tecnigraf.</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>/style.css">
</head>
<body>
    <header>
        <h1>Tecnigraf.</h1>
        <div class="user-info">
            <span>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <span class="catalogo-tag">Catálogo: <?php echo ucfirst($_SESSION['catalogo']); ?></span>
            <a href="<?php echo base_url(); ?>/logout.php" class="logout-button">Cerrar Sesión</a>
        </div>
    </header>

    <main>
        <section id="catalogo-productos">
            <h2>Nuestro Catálogo</h2>
            <div class="productos-container">
                <?php foreach ($productos as $producto): ?>
                <div class="producto-card" data-id="<?php echo $producto['id']; ?>" 
                     data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                     data-precio="<?php echo $producto['precio']; ?>"
                     data-catalogo="<?php echo $producto['catalogo']; ?>">
                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p class="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <p class="precio-unitario">Precio: <?php echo '€' . number_format($producto['precio'], 2); ?></p>
                    <div class="interaccion-producto">
                        <button class="btn-anadir-carrito" 
                                data-id="<?php echo $producto['id']; ?>"
                                data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                data-precio="<?php echo $producto['precio']; ?>">
                            Añadir al carrito
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="formulario-pedido">
            <h2>Realizar Pedido</h2>
            <form id="pedido-form">
                <div class="form-grupo">
                    <label for="nombre-cliente">Nombre Completo:</label>
                    <input type="text" id="nombre-cliente" name="nombre" required>
                </div>
                <div class="form-grupo">
                    <label for="direccion-envio">Dirección de Envío:</label>
                    <input type="text" id="direccion-envio" name="direccion" required>
                </div>
                <div class="form-grupo">
                    <label for="fecha-pedido">Fecha del Pedido:</label>
                    <input type="date" id="fecha-pedido" name="fecha" required>
                </div>
                <h3>Resumen del Pedido</h3>
                <div id="resumen-pedido">
                    <p>Añade productos del catálogo para ver el resumen aquí.</p>
                </div>
                <div id="total-pedido-final">
                    <strong>Total del Pedido: €0.00</strong>
                </div>
                <button type="submit">Enviar Pedido por Correo</button>
            </form>
        </section>
    </main>
    
    <footer>
        <p>© <?php echo date('Y'); ?> Tecnigraf. - Todos los derechos reservados.</p>
    </footer>

    <!-- Carrito -->
    <div class="cart-icon">
        🛒 <span id="cart-count">0</span>
    </div>
    <section id="carrito" style="display:none;">
        <h2>Tu Carrito</h2>
        <div id="cart-items"></div>
        <div id="cart-total">Total: €0.00</div>
        <button id="checkout-btn">Proceder al Pago</button>
    </section>

    <script src="<?php echo base_url(); ?>/script.js"></script>
</body>
</html> 