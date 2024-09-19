document.querySelector('title').textContent = 'Quickstock';
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '20px';
MAIN.style.paddingBottom = '100px';
MAIN.classList.add('container');
const RECUPERACION_API = 'servicios/administrador/recuperacion.php';
const SAVE_MODAL = new bootstrap.Modal('#recuperar');
const MAIN_TITLE = document.getElementById('mainTitle');
MAIN_TITLE.classList.add('text-center', 'py-3');
// Constante para establecer el formulario de registro del primer usuario.
const SIGNUP_FORM = document.getElementById('registrarse');
// Constante para establecer el formulario de inicio de sesión.
const LOGIN_FORM = document.getElementById('loginForm');
const ENVIAR = document.getElementById('enviar');
const INPUT1 = document.getElementById('email');

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    // loadTemplate();
    // Petición para consultar los usuarios registrados.
    const DATA = await fetchData(USER_API, 'readUsers');
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (DATA.session) {
        // Se direcciona a la página web de bienvenida.
        location.href = 'dashboard.html';
    } else if (DATA.status) {
        // Se establece el título del contenido principal.
        MAIN_TITLE.textContent = 'Iniciar sesión';
        // Se muestra el formulario para iniciar sesión.
        LOGIN_FORM.classList.remove('d-none');
        SIGNUP_FORM.remove();
        sweetAlert(4, DATA.message, true);
    } else {
        // Se establece el título del contenido principal.
        MAIN_TITLE.textContent = 'Registrar primer usuario';
        // Se muestra el formulario para registrar el primer usuario.
        SIGNUP_FORM.classList.remove('d-none');
        sweetAlert(4, DATA.error, true);
    }
    
    ENVIAR.addEventListener('click', async (event) => {
        if (INPUT1.value === '') {
                sweetAlert(2, 'El campo del correo no puede estar vacío.', false);
                return false;
        }
        event.preventDefault();

        const fechaActualUTC = new Date();
        const FORM = new FormData();
        FORM.append('correo', INPUT1.value);
        FORM.append('nivel', 1);
        FORM.append('fecha', fechaActualUTC);
        const DATA = await fetchData(RECUPERACION_API, 'envioCorreo', FORM);
        if (DATA.status) {
                sweetAlert(1, DATA.message, true);
                INPUT1.value = '';
                SAVE_MODAL.hide()
        } else {
                sweetAlert(2, DATA.error, false);
                console.log(DATA.error);
        }
});
});

// Método del evento para cuando se envía el formulario de registro del primer usuario.
SIGNUP_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SIGNUP_FORM);
    // Petición para registrar el primer usuario del sitio privado.
    const DATA = await fetchData(USER_API, 'registrarse', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        sweetAlert(1, DATA.message, true, 'index.html');
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

// Método del evento para cuando se envía el formulario de inicio de sesión.
LOGIN_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(LOGIN_FORM);
    // Petición para iniciar sesión.
    const DATA = await fetchData(USER_API, 'logIn', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        sweetAlert(1, DATA.message, true, 'dashboard.html');
    } else {
        sweetAlert(2, DATA.error, false);
    }
});