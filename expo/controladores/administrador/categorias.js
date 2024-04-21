// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#guardar_categoria');
const UPDATE_MODAL = new bootstrap.Modal('#actualizar_categoria');
MODAL_TITLE = document.getElementById('titulo_modal1');
MODAL_TITLE2 = document.getElementById('titulo_modal2');
// titulo de la pagina
MAIN_TITLE.textContent = 'Administrar Categorias';

// modal para agregar datos
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Agregar Categoria';
    // Se prepara el formulario.
    formulario_guardar.reset();
}

// modal para actualizar datos
const openUpdate = () => {
    // Se muestra la caja de diálogo con su título.
    UPDATE_MODAL.show();
    MODAL_TITLE2.textContent = 'Actualizar Categoria';
    // Se prepara el formulario.
    formulario_actualizar.reset();
}
