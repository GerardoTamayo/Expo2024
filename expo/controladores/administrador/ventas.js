// Constante para completar la ruta de la API.
const VENTA_API = 'servicios/administrador/venta.php';
const  CLIENTE_API = 'servicios/administrador/cliente.php';
const  PRODUCTO_API = 'servicios/administrador/producto.php';
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#guardar_cliente');
const SAVE_MODAL_DETALLE = new bootstrap.Modal('#guardar_detalle');
MODAL_TITLE = document.getElementById('titulo_modal');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('formulario_guardar'),
    ID_VENTA = document.getElementById('id_venta'),
    OBSERVACION_VENTA = document.getElementById('observacion_venta'),
    ESTADO_VENTA = document.getElementById('estado_venta'),
    VENDEDOR_VENTA = document.getElementById('id_vendedor'),
    ID_CLIENTE = document.getElementById('id_cliente'),
    FECHA_VENTA = document.getElementById('fecha_venta');
    // Constantes para establecer los elementos del formulario de guardar detalle compra.
const SAVE_FORM_DETALLE = document.getElementById('formulario_detalle'),
ID_DETALLE_VENTA = document.getElementById('id_detalle_venta'),
CANTIDAD_DETALLE = document.getElementById('cantidad'),
PRECIO_DETALLE = document.getElementById('precio'),
PRODUCTO_DETALLE = document.getElementById('producto'),
VENTA_DETALLE = document.getElementById('venta');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY_DETALLE = document.getElementById('tableBodyDetalle'),
ROWS_FOUND_DETALLE = document.getElementById('rowsFoundDetalle');

let idventa = null;
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Administrar ventas';
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
    (ID_VENTA.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(VENTA_API, action, FORM);
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
    const DATA = await fetchData(VENTA_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.FECHA}</td>
                    <td>${row.OBSERVACION}</td>
                    <td>${row.ESTADO_FINAL}</td>
                    <td>${row.CLIENTE}</td>
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
    MODAL_TITLE.textContent = 'Agregar venta';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    fillSelect(CLIENTE_API, 'readAll', 'id_cliente');
}

const openUpdate = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_venta', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(VENTA_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar venta';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_VENTA.value = ROW.id_venta;
        FECHA_VENTA.value = ROW.fecha_venta;
        OBSERVACION_VENTA.value = ROW.observacion_venta;
        ESTADO_VENTA.checked = ROW.estado_venta;
        fillSelect(CLIENTE_API, 'readAll', 'id_cliente', ROW.id_cliente);
    } else {
        sweetAlert(2, DATA.error, false);
    }
}


const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar este cliente de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_venta', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(VENTA_API, 'deleteRow', FORM);
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
    (ID_DETALLE_VENTA.value) ? action = 'updateRow1' : action = 'createRow1';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM_DETALLE);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(VENTA_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        SAVE_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        const FORM = new FormData();
        FORM.append('id_venta', idventa)
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
    const DATA = await fetchData(VENTA_API, 'readAll1', form);
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
                    <td>${row.VENTA}</td>
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


const openDetalle = (id) => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL_DETALLE.show();
        // Se prepara el formulario.
    SAVE_FORM_DETALLE.reset();
    fillSelect(PRODUCTO_API, 'readAll', 'producto');
    fillSelect(VENTA_API, 'readAll', 'venta');
    idventa = id;
    const FORM = new FormData();
    FORM.append('id_venta', id)
    fillTableDetalle(FORM);
}

const openUpdateDetalle = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_detalle_venta', id);
    
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(VENTA_API, 'readOne1', FORM);
    
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        
        // Cambiar al tab del formulario
        const formularioTab = new bootstrap.Tab(document.getElementById('formulario-tab'));
        formularioTab.show();
        
        // Se prepara el formulario.
        SAVE_FORM.reset();
        
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        document.getElementById('id_detalle_venta').value = ROW.ID;
        document.getElementById('cantidad').value = ROW.CANTIDAD;
        document.getElementById('precio').value = ROW.PRECIO;
        document.getElementById('producto').value = ROW.PRODUCTO;
        document.getElementById('venta').value = ROW.VENTA;
        
        // Llenar el select si es necesario
        fillSelect(PRODUCTO_API, 'readAll', 'producto', ROW.ID_PRODUCTO);
        fillSelect(VENTA_API, 'readAll', 'venta', ROW.ID_VENTA);
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

const openDeleteDetalle = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar este detalle de venta de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_detalle_venta', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(VENTA_API, 'deleteRow1', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            const FORM = new FormData();
            FORM.append('id_venta', idventa)
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTableDetalle(FORM);
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}

