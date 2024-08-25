// Constante para completar la ruta de la API.
const COMPRA_API = 'servicios/administrador/compra.php';
const PROVEEDOR_API = 'servicios/administrador/vendedor.php';
const PRODUCTO_API = 'servicios/administrador/producto.php';
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#guardar_cliente');
const SAVE_MODAL_DETALLE = new bootstrap.Modal('#guardar_detalle');
MODAL_TITLE = document.getElementById('titulo_modal');
MODAL_TITLE_DETALLE = document.getElementById('titulo_detalle');
// Constantes para establecer los elementos del formulario de guardar compra.
const SAVE_FORM = document.getElementById('formulario_guardar'),
    ID_COMPRA = document.getElementById('id_compra'),
    NUMERO_CORRELATIVO = document.getElementById('numero_correlativo'),
    ESTADO_COMPRA = document.getElementById('estado_compra'),
    VENDEDOR_COMPRA = document.getElementById('id_proveedor'),
    FECHA_COMPRA = document.getElementById('fecha_compra');
// Constantes para establecer los elementos del formulario de guardar detalle compra.
const SAVE_FORM_DETALLE = document.getElementById('formulario_detalle'),
    ID_DETALLE = document.getElementById('id_detalle_compra'),
    CANTIDAD_DETALLE = document.getElementById('cantidad'),
    PRECIO_DETALLE = document.getElementById('precio'),
    PRODUCTO_DETALLE = document.getElementById('producto'),
    CORRELATIVO_DETALLE = document.getElementById('correlativo');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY_DETALLE = document.getElementById('tableBodyDetalle'),
    ROWS_FOUND_DETALLE = document.getElementById('rowsFoundDetalle');

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Administrar compras';
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
    (ID_COMPRA.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(COMPRA_API, action, FORM);
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
    const DATA = await fetchData(COMPRA_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.FECHA}</td>
                    <td>${row.CORRELATIVO}</td>
                    <td>${row.ESTADO_DESC}</td>
                    <td>${row.PROVEEDOR}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info" onclick="openUpdate(${row.ID})">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="openDelete(${row.ID})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="openDetalle(${row.ID})">
                            <i class="bi bi-layout-text-sidebar-reverse"></i>
                            <span>Detalle</span>
                        </button>
                        <button type="button" class="btn btn-warning" onclick="openReport(${row.id_proveedor})">
                            <i class="bi bi-filetype-pdf"></i>
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
    MODAL_TITLE.textContent = 'Agregar compra';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    fillSelect(PROVEEDOR_API, 'readAll', 'id_vendedor');
}

const openUpdate = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_compra', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(COMPRA_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar compra';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_COMPRA.value = ROW.id_compra;
        NUMERO_CORRELATIVO.value = ROW.numero_correlativo;
        FECHA_COMPRA.value = ROW.fecha_compra;
        ESTADO_COMPRA.checked = ROW.estado_compra;
        fillSelect(PROVEEDOR_API, 'readAll', 'id_vendedor', ROW.id_proveedor);
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar esta compra de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_compra', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(COMPRA_API, 'deleteRow', FORM);
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

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM_DETALLE.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (ID_DETALLE.value) ? action = 'actualizarCompra' : action = 'agregarCompra';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_DETALLE);
    FORM.append('id_compra', id_compra_global);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(COMPRA_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        SAVE_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        const FORM = new FormData();
        FORM.append('id_compra', id_compra_global);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTableDetalle(FORM);
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTableDetalle = async (form) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND_DETALLE.textContent = '';
    TABLE_BODY_DETALLE.innerHTML = '';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(COMPRA_API, 'readAll1', form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY_DETALLE.innerHTML += `
                <tr>
                    <td>${row.CANTIDAD}</td>
                    <td>${row.PRECIO}</td>
                    <td>${row.PRODUCTO}</td>
                    <td>${row.CORRELATIVO}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info" onclick="openUpdateDetalle(${row.ID})">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="openDeleteDetalle(${row.ID})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND_DETALLE.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

let id_compra_global = null;
const openDetalle = async (id) => {
    const FORM = new FormData();
    FORM.append('id_compra', id);
    console.log(id)
    const DATA = await fetchData(COMPRA_API, 'readOne', FORM);
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL_DETALLE.show();
        MODAL_TITLE_DETALLE.textContent = 'Agregar detalle compra';
        // Se prepara el formulario.
        SAVE_FORM_DETALLE.reset();
        fillSelect(PRODUCTO_API, 'readAll', 'producto');
        fillTableDetalle(FORM);
        id_compra_global = id;
        console.log(id_compra_global);
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

const openUpdateDetalle = async (id, quantity) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_detalle_compra', id);

    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(COMPRA_API, 'readOne1', FORM);

    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {

        // Cambiar al tab del formulario
        const formularioTab = new bootstrap.Tab(document.getElementById('formulario-tab'));
        formularioTab.show();

        // Se prepara el formulario.
        SAVE_FORM.reset();

        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        document.getElementById('id_detalle_compra').value = id;
        document.getElementById('cantidad').value = ROW.CANTIDAD;
        document.getElementById('precio').value = ROW.PRECIO;
        document.getElementById('producto').value = ROW.PRODUCTO;

        // Llenar el select si es necesario
        fillSelect(PRODUCTO_API, 'readAll', 'producto', ROW.ID_PRODUCTO);
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

const openDeleteDetalle = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar este detalle de compra de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_detalle_compra', id);
        FORM.append('id_compra', id_compra_global);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(COMPRA_API, 'eliminarCompra', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            const FORM = new FormData();
            FORM.append('id_compra', id_compra_global);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTableDetalle(FORM);
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}

const openReport = (id) => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reportes/administrador/proveedor_compra.php`);
    // Se agrega un parámetro a la ruta con el valor del registro seleccionado.
    PATH.searchParams.append('id_proveedor', id);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}


