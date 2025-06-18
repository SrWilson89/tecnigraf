// Espera a que todo el contenido del DOM esté cargado antes de ejecutar el script.
document.addEventListener('DOMContentLoaded', () => {

    // --- CONFIGURACIÓN ---
    // Dirección de correo a la que se enviará el pedido.
    // Cámbiala por la dirección de tu empresa.
    const CORREO_EMPRESA = 'ventas@miempresa.com';

    // --- SELECCIÓN DE ELEMENTOS DEL DOM ---
    // Se buscan los elementos en el HTML con los que vamos a interactuar.
    const catalogoContainer = document.getElementById('catalogo-productos');
    const formularioPedido = document.getElementById('pedido-form');
    const resumenPedidoDiv = document.getElementById('resumen-pedido');
    const totalPedidoFinalDiv = document.getElementById('total-pedido-final');

    // --- FUNCIONES ---

    /**
     * Formatea un número a una cadena de texto con formato de moneda (Euro).
     * @param {number} numero - El número a formatear.
     * @returns {string} El número formateado como moneda (ej. "123,45 €").
     */
    const formatearMoneda = (numero) => {
        return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(numero);
    };

    /**
     * Actualiza el precio total de un producto individual basado en la cantidad.
     * Esta función se llama cada vez que el usuario cambia la cantidad de un producto.
     * @param {HTMLInputElement} inputCantidad - El campo de entrada de cantidad que cambió.
     */
    const actualizarPrecioProducto = (inputCantidad) => {
        // Busca el contenedor 'padre' del producto para obtener sus datos.
        const productoCard = inputCantidad.closest('.producto-card');
        const precioUnitario = parseFloat(productoCard.dataset.precio);
        const cantidad = parseInt(inputCantidad.value) || 0;
        const precioTotal = precioUnitario * cantidad;

        // Actualiza el texto que muestra el total para ese producto.
        const precioTotalElemento = productoCard.querySelector('.precio-total-producto');
        precioTotalElemento.textContent = `Total: ${formatearMoneda(precioTotal)}`;
        
        // Una vez actualizado un producto, se recalcula el resumen completo del pedido.
        actualizarResumenGeneral();
    };

    /**
     * Actualiza la sección "Resumen del Pedido" y el total final.
     * Recorre todos los productos del catálogo y muestra solo los que tienen una cantidad mayor a cero.
     */
    const actualizarResumenGeneral = () => {
        let totalGeneral = 0;
        let resumenHTML = '<ul>';
        let hayProductos = false;

        const todosLosProductos = document.querySelectorAll('.producto-card');

        // Itera sobre cada producto en el catálogo.
        todosLosProductos.forEach(producto => {
            const cantidad = parseInt(producto.querySelector('.cantidad').value) || 0;
            
            // Solo procesa productos con cantidad mayor a 0.
            if (cantidad > 0) {
                hayProductos = true;
                const nombre = producto.dataset.nombre;
                const precioUnitario = parseFloat(producto.dataset.precio);
                const totalProducto = cantidad * precioUnitario;
                totalGeneral += totalProducto;

                // Añade una línea al resumen por cada producto seleccionado.
                resumenHTML += `<li>${nombre} - ${cantidad} x ${formatearMoneda(precioUnitario)} = <strong>${formatearMoneda(totalProducto)}</strong></li>`;
            }
        });

        resumenHTML += '</ul>';

        // Muestra el resumen o un mensaje por defecto si no hay productos.
        if (hayProductos) {
            resumenPedidoDiv.innerHTML = resumenHTML;
        } else {
            resumenPedidoDiv.innerHTML = '<p>Añade productos del catálogo para ver el resumen aquí.</p>';
        }

        // Actualiza el precio total final del pedido.
        totalPedidoFinalDiv.innerHTML = `<strong>Total del Pedido: ${formatearMoneda(totalGeneral)}</strong>`;
    };

    /**
     * Recopila toda la información del pedido y genera un enlace mailto:
     * para abrir el cliente de correo del usuario con los datos pre-rellenados.
     * @param {Event} event - El evento de envío del formulario.
     */
    const enviarPedidoPorCorreo = (event) => {
        event.preventDefault(); // Previene el envío tradicional del formulario que recargaría la página.

        // Recopilar datos del cliente desde el formulario.
        const nombreCliente = document.getElementById('nombre-cliente').value;
        const direccionEnvio = document.getElementById('direccion-envio').value;
        const fechaPedido = document.getElementById('fecha-pedido').value;

        // Validar que haya productos en el pedido antes de continuar.
        const productosEnPedido = Array.from(document.querySelectorAll('.producto-card')).filter(p => parseInt(p.querySelector('.cantidad').value) > 0);
        
        if (productosEnPedido.length === 0) {
            alert('Por favor, añade al menos un producto a tu pedido antes de enviarlo.');
            return; // Detiene la ejecución de la función si no hay productos.
        }

        // Construir el cuerpo del correo electrónico.
        let cuerpoCorreo = `Nuevo Pedido Recibido\n`;
        cuerpoCorreo += `-------------------------\n`;
        cuerpoCorreo += `Nombre del Cliente: ${nombreCliente}\n`;
        cuerpoCorreo += `Dirección de Envío: ${direccionEnvio}\n`;
        cuerpoCorreo += `Fecha del Pedido: ${fechaPedido}\n\n`;
        cuerpoCorreo += `Detalles del Pedido:\n`;
        cuerpoCorreo += `-------------------------\n`;
        
        let totalGeneral = 0;
        productosEnPedido.forEach(producto => {
            const nombre = producto.dataset.nombre;
            const cantidad = producto.querySelector('.cantidad').value;
            const precioUnitario = parseFloat(producto.dataset.precio);
            const totalProducto = cantidad * precioUnitario;
            totalGeneral += totalProducto;
            cuerpoCorreo += `- ${nombre}: ${cantidad} ud(s). x ${formatearMoneda(precioUnitario)} = ${formatearMoneda(totalProducto)}\n`;
        });

        cuerpoCorreo += `\n-------------------------\n`;
        cuerpoCorreo += `TOTAL DEL PEDIDO: ${formatearMoneda(totalGeneral)}\n`;

        // Crear el enlace mailto: codificando el asunto y el cuerpo para evitar errores con caracteres especiales.
        const asunto = encodeURIComponent(`Nuevo Pedido de: ${nombreCliente}`);
        const cuerpo = encodeURIComponent(cuerpoCorreo);
        const mailtoLink = `mailto:${CORREO_EMPRESA}?subject=${asunto}&body=${cuerpo}`;

        // Abrir el cliente de correo predeterminado del usuario.
        window.location.href = mailtoLink;
    };

    // --- ASIGNACIÓN DE EVENTOS ---

    // Se usa la "delegación de eventos" en el contenedor del catálogo.
    // Esto es más eficiente que añadir un evento a cada input por separado.
    // El evento 'input' se dispara inmediatamente cuando el valor cambia.
    catalogoContainer.addEventListener('input', (event) => {
        if (event.target.classList.contains('cantidad')) {
            actualizarPrecioProducto(event.target);
        }
    });

    // Asigna la función de envío al evento 'submit' del formulario.
    formularioPedido.addEventListener('submit', enviarPedidoPorCorreo);

    // --- INICIALIZACIÓN ---
    
    // Al cargar la página, establece la fecha del pedido al día actual.
    // Esto mejora la experiencia del usuario.
    const fechaInput = document.getElementById('fecha-pedido');
    if(fechaInput) {
        fechaInput.valueAsDate = new Date();
    }
});