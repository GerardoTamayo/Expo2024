// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#guardar_producto');
const UPDATE_MODAL = new bootstrap.Modal('#actualizar_producto');
MODAL_TITLE = document.getElementById('titulo_modal');
MODAL_TITLE2 = document.getElementById('titulo_modal2');
// titulo de la pagina
MAIN_TITLE.textContent = 'Administrar Productos';

// modal para agregar datos
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Agregar Producto';
    // Se prepara el formulario.
    formulario_guardar.reset();
}

// modal para actualizar datos
const openUpdate = () => {
    // Se muestra la caja de diálogo con su título.
    UPDATE_MODAL.show();
    MODAL_TITLE2.textContent = 'Actualizar Producto';
    // Se prepara el formulario.
    formulario_actualizar.reset();
}

const openDelete = async () => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmarAction('¿Desea eliminar este producto de forma permanente?');
}