// Constante para completar la ruta de la API.
const VENDEDOR_API = 'servicios/administrador/vendedor.php';
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#guardar_proveedor');
// Titulos de las modals
MODAL_TITLE = document.getElementById('titulo_modal');
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('formulario_guardar'),
ID_PROVEEDOR = document.getElementById('id_proveedor'),
NOMBRE_PROVEEDOR = document.getElementById('nombre_proveedor'),
APELLIDO_PROVEEDOR = document.getElementById('apellido_proveedor'),
TELEFONO_PROVEEDOR = document.getElementById('telefono_proveedor'),
CORREO_PROVEEDOR = document.getElementById('correo_proveedor');

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Administrar vendedores';
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
    (ID_PROVEEDOR.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(VENDEDOR_API, action, FORM);
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
    const DATA = await fetchData(VENDEDOR_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.nombre_proveedor}</td>
                    <td>${row.apellido_proveedor}</td>
                    <td>${row.correo_proveedor}</td>
                    <td>${row.telefono_proveedor}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info" onclick="openUpdate(${row.id_proveedor})">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="openDelete(${row.id_proveedor})">
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
    MODAL_TITLE.textContent = 'Agregar vendedor';
    // Se prepara el formulario.
    SAVE_FORM.reset();
}



const openDelete = async () => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmarAction('¿Desea eliminar este vendedor de forma permanente?');
}

// Llamada a la función para establecer la mascara del campo teléfono.
vanillaTextMask.maskInput({
    inputElement: document.getElementById('telefono_proveedor'),
    mask: [/\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/]
});
