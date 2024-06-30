// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#guardar_cliente');
ID_CLIENTE2 = document.getElementById('id_cliente2'),
NOMBRE_CLIENTE2 = document.getElementById('nombre_cliente2'),
APELLIDO_CLIENTE2 = document.getElementById('apellido_cliente2'),
TELEFONO_CLIENTE2 = document.getElementById('telefono_cliente2'),
DUI_CLIENTE2 = document.getElementById('dui_cliente2'),
DIRECCION_CLIENTE2 = document.getElementById('direccion_cliente2'),
CORREO_CLIENTE2 = document.getElementById('correo_cliente2');
// Titulos de las modals
MODAL_TITLE = document.getElementById('titulo_modal');
MODAL_TITLE2 = document.getElementById('titulo_modal2');

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Administrar clientes';
})

// modal para agregar datos
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Agregar cliente';
    // Se prepara el formulario.
    formulario_guardar.reset();
}

// modal para actualizar datos
const openUpdate = () => {
    // Se muestra la caja de diálogo con su título.
    UPDATE_MODAL.show();
    MODAL_TITLE2.textContent = 'Actualizar cliente';
    // Se prepara el formulario.
    formulario_actualizar.reset();
    DUI_CLIENTE2.disabled = true;
}

const openDelete = async () => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmarAction('¿Desea eliminar este cliente de forma permanente?');
}

// Llamada a la función para establecer la mascara del campo teléfono.
vanillaTextMask.maskInput({
    inputElement: document.getElementById('telefono_cliente'),
    mask: [/\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/]
});

// Llamada a la función para establecer la mascara del campo teléfono para actualizar.
vanillaTextMask.maskInput({
    inputElement: document.getElementById('telefono_cliente2'),
    mask: [/\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/]
});

// Llamada a la función para establecer la mascara del campo DUI.
vanillaTextMask.maskInput({
    inputElement: document.getElementById('dui_cliente'),
    mask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/]
});