// Constantes para completar las rutas de la API.
const PRODUCTO_API = 'servicios/administrador/producto.php';
const CATEGORIA_API = 'servicios/administrador/categoria.php';
const MARCA_API = 'servicios/administrador/marca.php'
const PRESENTACION_API = 'servicios/administrador/presentacion.php'
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer el contenido de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#guardar_producto');
MODAL_TITLE = document.getElementById('titulo_modal');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('formulario_guardar'),
    ID_PRODUCTO = document.getElementById('id_producto'),
    NOMBRE_PRODUCTO = document.getElementById('nombre_producto'),
    DESCRIPCION_PRODUCTO = document.getElementById('descripcion_producto'),
    EXISTENCIA_PRODUCTO = document.getElementById('existencias_producto'),
    MARCA_PRODUCTO = document.getElementById('marca_producto'),
    PRESENTACION_PRODUCTO = document.getElementById('presentacion_producto')
FECHA_PRODUCTO = document.getElementById('fecha_producto')
CATEGORIA_PRODUCTO = document.getElementById('categoria_producto');

const REPORT_MODAL = new bootstrap.Modal('#reportModal'),
    REPORT_MODAL_TITLE = document.getElementById('reportModalTitle');

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Administrar productos';
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
})

// Método del evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (ID_PRODUCTO.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(PRODUCTO_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        SAVE_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTable = async (form = null) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(PRODUCTO_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.nombre_producto}</td>
                    <td>${row.fecha_vencimiento}</td>
                    <td>${row.descripcion}</td>
                    <td>${row.existencias_producto}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info" onclick="openUpdate(${row.id_producto})">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="openDelete(${row.id_producto})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

// modal para agregar datos
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Agregar producto';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    fillSelect(CATEGORIA_API, 'readAll', 'categoria_producto');
    fillSelect(MARCA_API, 'readAll', 'marca_producto');
    fillSelect(PRESENTACION_API, 'readAll', 'presentacion_producto');
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    console.log(id);
    // Se define un objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_producto', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(PRODUCTO_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar producto';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_PRODUCTO.value = ROW.id_producto;
        NOMBRE_PRODUCTO.value = ROW.nombre_producto;
        FECHA_PRODUCTO.value = ROW.fecha_vencimiento;
        DESCRIPCION_PRODUCTO.value = ROW.descripcion;
        EXISTENCIA_PRODUCTO.value = ROW.existencias_producto;
        fillSelect(CATEGORIA_API, 'readAll', 'categoria_producto', ROW.id_categoria);
        fillSelect(MARCA_API, 'readAll', 'marca_producto', ROW.id_marca);
        fillSelect(PRESENTACION_API, 'readAll', 'presentacion_producto', ROW.id_tipo_presentacion);
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el producto de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_producto', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(PRODUCTO_API, 'deleteRow', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}

/*
*   Función para abrir un reporte automático de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openReport = () => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reportes/administrador/inventario_producto.php`);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}

async function openModalGraphic() {
    // Se muestra la caja de diálogo con su título.
    REPORT_MODAL.show();
    REPORT_MODAL_TITLE.textContent = 'Gráfica de barra de productos por marcas';
    try {
        graficoDonaProductoMarcas();
    } catch (error) {
        console.log(error);
    }
}

const graficoDonaProductoMarcas = async () => {
    try {
        // Petición para obtener los datos del gráfico.
        const DATA = await fetchData(PRODUCTO_API, 'graficaProducto');
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a gráficar.
            let nombre_marca = [];
            let existencias_producto = [];
            // Se recorre el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                // Se agregan los datos a los arreglos.
                nombre_marca.push(row.MARCA);
                existencias_producto.push(row.EXISTENCIAS);
            });
            // Llamada a la función para generar y mostrar un gráfico de pastel. Se encuentra en el archivo components.js
            barGraph('chart2', nombre_marca, existencias_producto, 'Productos por marcas');
        } else {
            document.getElementById('chart2').remove();
            console.log(DATA.error);
        }
    } catch (error) {
        console.log('error:', error);
    }
}