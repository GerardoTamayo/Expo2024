// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#guardar_proveedor');
const UPDATE_MODAL = new bootstrap.Modal('#actualizar_proveedor');
// Titulos de las modals
MODAL_TITLE = document.getElementById('titulo_modal');
MODAL_TITLE2 = document.getElementById('titulo_modal2');
// titulo de la pagina
MAIN_TITLE.textContent = 'Administrar Usuarios';

// modal para agregar datos
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Agregar Vendedor';
    // Se prepara el formulario.
    formulario_guardar.reset();
}

// modal para actualizar datos
const openUpdate = () => {
    // Se muestra la caja de diálogo con su título.
    UPDATE_MODAL.show();
    MODAL_TITLE2.textContent = 'Actualizar Vendedor';
    // Se prepara el formulario.
    formulario_actualizar.reset();
}

// Llamada a la función para establecer la mascara del campo teléfono.
vanillaTextMask.maskInput({
    inputElement: document.getElementById('telefono_proveedor'),
    mask: [/\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/]
});

vanillaTextMask.maskInput({
    inputElement: document.getElementById('telefono_proveedor2'),
    mask: [/\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/]
});
