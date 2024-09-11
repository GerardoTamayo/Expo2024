document.querySelector('title').textContent = 'Quickstock';
const MAIN = document.querySelector('main');
MAIN.style.paddingTop = '20px';
MAIN.style.paddingBottom = '100px';
MAIN.classList.add('container');

const MAIN_TITLE = document.getElementById('mainTitle');
MAIN_TITLE.classList.add('text-center', 'py-3');
// Constante para establecer el formulario de inicio de sesión.
const LOGIN_FORM = document.getElementById('loginForm');
const ENVIAR = document.getElementById('enviar');

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
        // Se establece el título del contenido principal.
        MAIN_TITLE.textContent = 'Cambiar contraseña';
        // Se muestra el formulario para iniciar sesión.
        LOGIN_FORM.classList.remove('d-none');

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
                const DATA = await fetchData(API, 'envioCorreo', FORM);
                if (DATA.status) {
                        sweetAlert(1, DATA.message, true);
                } else {
                        sweetAlert(2, DATA.error, false);
                        console.error(DATA.exception);
                }
        });
});
