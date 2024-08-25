
// Constante para completar la ruta de la API.
const USUARIO_API = 'servicios/administrador/usuario.php';
const TIPO_USUARIO_API = 'servicios/administrador/tipo_usuario.php'
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#guardar_usuario');
// Titulos de las modals
MODAL_TITLE = document.getElementById('titulo_modal');
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('formulario_guardar'),
    ID_USUARIO = document.getElementById('id_usuario'),
    NOMBRE_USUARIO = document.getElementById('nombre_usuario'),
    APELLIDO_USUARIO = document.getElementById('apellido_usuario'),
    CORREO_USUARIO = document.getElementById('correo_usuario'),
    TIPO_USUARIO = document.getElementById('tipo_usuario'),
    CLAVE_USUARIO = document.getElementById('clave_usuario'),
    CONFIRMAR_CLAVE = document.getElementById('repetir_contra');
    CHART_MODAL = new bootstrap.Modal('#chartModal');
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
    const DATA = await fetchData(USUARIO_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.nombre_usuario}</td>
                    <td>${row.apellido_usuario}</td>
                    <td>${row.correo_usuario}</td>
                    <td>${row.tipo_usuario}</td>
                    <td>${row.ESTADO}</td>
                    <td>
                        <button type="button" class="btn btn-outline-info" onclick="openUpdate(${row.id_usuario})">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="openDelete(${row.id_usuario})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="openState(${row.id_usuario})">
                            <i class="bi bi-exclamation-octagon"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="openChart(${row.id_usuario})">
                            <i class="bi bi-exclamation-octagon"></i>
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

/*
*   Función asíncrona para cambiar el estado de un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openState = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea cambiar el estado del usuario?');
    try {
        // Se verifica la respuesta del mensaje.
        if (RESPONSE) {
            // Se define una constante tipo objeto con los datos del registro seleccionado.
            const FORM = new FormData();
            FORM.append('id_usuario', id);
            // Petición para eliminar el registro seleccionado.
            const DATA = await fetchData(USUARIO_API, 'changeState', FORM);
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
    catch (Error) {
        sweetAlert(2, Error, false);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/

const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Crear usuario';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    // TIPO_USUARIO.disabled = false;
    CLAVE_USUARIO.disabled = false;
    CONFIRMAR_CLAVE.disabled = false;
    fillSelect(TIPO_USUARIO_API, 'readAll', 'tipo_usuario');
}


/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_usuario', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(USUARIO_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar usuario';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        CLAVE_USUARIO.disabled = true;
        CONFIRMAR_CLAVE.disabled = true;
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_USUARIO.value = ROW.id_usuario;
        NOMBRE_USUARIO.value = ROW.nombre_usuario;
        APELLIDO_USUARIO.value = ROW.apellido_usuario;
        CORREO_USUARIO.value = ROW.correo_usuario;
        fillSelect(TIPO_USUARIO_API, 'readAll', 'tipo_usuario', ROW.id_tipo);
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
    const RESPONSE = await confirmAction('¿Desea eliminar el usuario de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_usuario', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(USUARIO_API, 'deleteRow', FORM);
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



// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar usuarios';
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
});

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
    (ID_USUARIO.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(USUARIO_API, action, FORM);
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

const openChart = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_usuario', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(USUARIO_API, 'graficaUsuario', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con el error.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        CHART_MODAL.show();
        // Se declaran los arreglos para guardar los datos a graficar.
        let estado = [];
        let cantidad = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            estado.push(row.Estado_Usuario);
            cantidad.push(row.Cantidad_Usuarios);
        });
        // Se agrega la etiqueta canvas al contenedor de la modal.
        document.getElementById('chartContainer').innerHTML = `<canvas id="chart"></canvas>`;
        // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
        barGraph('chart', estado, cantidad, 'Cantidad de Usuario', 'Usuarios por estado');
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función para abrir un reporte automático de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openReport = () => {
    // Se declara una constante tipo objeto con la ruta específica del reporte en el servidor.
    const PATH = new URL(`${SERVER_URL}reportes/administrador/estado_usuario.php`);
    // Se abre el reporte en una nueva pestaña.
    window.open(PATH.href);
}