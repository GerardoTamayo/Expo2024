const Inicio = document.getElementById('main');
document.querySelector('title').textContent = 'Quickstock';
const MAIN_TITLE = document.getElementById('mainTitle');
const MAIN = document.querySelector('container-fluid');
MAIN_TITLE.classList.add('text-center', 'py-3');

const loadTemplate = async () => {
    Inicio.insertAdjacentHTML('beforebegin', `
        <div class="col-auto col-md-3 col-lg-2 col-xl-2 px-sm-2 px-0 bg-info" id="menu">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a href="" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Menu</span>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                                    <li>
                        <a href="dashboard.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-person-badge-fill"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="productos.html" class="nav-link align-middle px-0 text-dark">
                            <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Productos</span>
                        </a>
                    </li>
                    <li>
                        <a href="presentaciones.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Presentaciones</span></a>
                    </li>
                    <li>
                        <a href="categorias.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-pin-angle-fill"></i> <span class="ms-1 d-none d-sm-inline">Categorias</span>
                        </a>
                    </li>
                    <li>
                        <a href="marcas.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-tags-fill"></i> <span class="ms-1 d-none d-sm-inline">Marcas</span>
                        </a>
                    </li>
                    <li>
                        <a href="usuarios.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-people"></i> <span class="ms-1 d-none d-sm-inline">Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="tipos_de_usuarios.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-people"></i> <span class="ms-1 d-none d-sm-inline">Tipos de
                                usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="clientes.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-people"></i> <span class="ms-1 d-none d-sm-inline">Clientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="compras.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-receipt"></i> <span class="ms-1 d-none d-sm-inline">Compras</span>
                        </a>
                    </li>
                    <li>
                        <a href="ventas.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-currency-dollar"></i> <span class="ms-1 d-none d-sm-inline">Ventas</span>
                        </a>
                    </li>
                    <li>
                        <a href="vendedores.html" class="nav-link px-0 align-middle text-dark">
                            <i class="fs-4 bi-person-badge-fill"></i> <span class="ms-1 d-none d-sm-inline">Vendedores</span>
                        </a>
                    </li>
                    <li>
                        <a href="perfil.html" class="nav-link px-0 align-middle text-dark">
                            <i class="bi bi-person-circle"></i> <span class="ms-1 d-none d-sm-inline">Perfil</span>
                        </a>
                    </li>
                </ul>
                <hr>
                <div>
                    <button type="button" class="btn btn-info" onclick="logOut()">Cerrar sesión <i
                        class="bi bi-box-arrow-left"></i></button>
                </div>
            </div>
        </div>
        `);

    Inicio.insertAdjacentHTML('beforeend', `
        <footer class="py-3 bg-body-light>
            <div class=" container-fluid clear">
            <p class="text-end text-muted">&copy; 2024 Distribuidora TMG El Salvador . Todos los derechos reservados.</p>
            </div>
        </footer>
        `);
}