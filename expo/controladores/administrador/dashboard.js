// Constante para completar la ruta de la API.
const VENTA_API = 'servicios/administrador/venta.php',
    PRODUCTO_API = 'servicios/administrador/producto.php',
    COMPRA_API = 'servicios/administrador/compra.php',
    CLIENTE_API = 'servicios/administrador/cliente.php';

document.addEventListener('DOMContentLoaded', () => {
    // Constante para obtener el número de horas.
    const HOUR = new Date().getHours();
    // Se define una variable para guardar un saludo.
    let greeting = '';
    // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
    if (HOUR < 12) {
        greeting = 'Buenos días';
    } else if (HOUR < 19) {
        greeting = 'Buenas tardes';
    } else if (HOUR <= 23) {
        greeting = 'Buenas noches';
    }
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = `${greeting}, bienvenido`;
    // Llamada a la función para mostrar en las cards.
    totalVentas();
    totalProductos();
    totalClientes();
    totalCompras();
});

async function totalVentas() {
    try {
        const DATA = await fetchData(VENTA_API, 'readTotalVenta');
        if (DATA.status) {
            const totalVenta = DATA.dataset[0].total_ventas;
            const mes = DATA.dataset[0].mes;
            document.getElementById('ventas').textContent = totalVenta;
            document.getElementById('mes').textContent = mes;
        } else {
            sweetAlert(4, DATA.error.true);
        }
    } catch (error) {
        console.error('Error: ', error);
    }
}

async function totalProductos() {
    try {
        const DATA = await fetchData(PRODUCTO_API, 'countAllProducts');
        console.log(DATA)
        if (DATA.status) {
            const totalProductos = DATA.dataset[0].TOTAL;
            document.getElementById('productos').textContent = totalProductos;
        } else {
            sweetAlert(4, DATA.error.true);
        }
    } catch (error) {
        console.error('Error: ', error);
    }
}

async function totalClientes() {
    try {
        const DATA = await fetchData(CLIENTE_API, 'countAll');
        console.log(DATA)
        if (DATA.status) {
            const totalClientes = DATA.dataset[0].CLIENTES;
            document.getElementById('clientes').textContent = totalClientes;
        } else {
            sweetAlert(4, DATA.error.true);
        }
    } catch (error) {
        console.error('Error: ', error);
    }
}

async function totalCompras() {
    try {
        const DATA = await fetchData(COMPRA_API, 'totalCompra');
        if (DATA.status) {
            const totalCompras = DATA.dataset[0].total_compras;
            const mes = DATA.dataset[0].mes;
            document.getElementById('compras').textContent = totalCompras;
            document.getElementById('mes2').textContent = mes;
        } else {
            sweetAlert(4, DATA.error.true);
        }
    } catch (error) {
        console.error('Error: ', error);
    }
}