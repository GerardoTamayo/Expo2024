const Inicio = document.getElementById('main');
const Footer = document.getElementById('footer')
document.querySelector('title').textContent = 'Quickstock';

const MAIN_TITLE = document.getElementById('mainTitle');
MAIN_TITLE.classList.add('text-center', 'py-3');

    Inicio.insertAdjacentHTML('beforebegin', `
    <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-info">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href=""
                        class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">Menu</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start"
                        id="menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link align-middle px-0 text-dark">
                                <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Productos</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-table"></i> <span
                                    class="ms-1 d-none d-sm-inline">Presentaciones</span></a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-pin-angle-fill"></i> <span
                                    class="ms-1 d-none d-sm-inline">Categorias</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-tags-fill"></i> <span class="ms-1 d-none d-sm-inline">Marcas</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-people"></i> <span class="ms-1 d-none d-sm-inline">Usuarios</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-file-earmark-check-fill"></i> <span
                                    class="ms-1 d-none d-sm-inline">Inventario</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-people"></i> <span class="ms-1 d-none d-sm-inline">Tipos de
                                    usuarios</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-people"></i> <span class="ms-1 d-none d-sm-inline">Clientes</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-receipt"></i> <span class="ms-1 d-none d-sm-inline">Compras</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-currency-dollar"></i> <span
                                    class="ms-1 d-none d-sm-inline">Ventas</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-0 align-middle text-dark">
                                <i class="fs-4 bi-person-badge-fill"></i> <span
                                    class="ms-1 d-none d-sm-inline">Vendedores</span>
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div>
                        <a href="#" class="d-flex align-items-center text-black text-decoration-none py-3">
                            <i class="fs-4 bi-box-arrow-left"></i><span class="d-none d-sm-inline mx-1">Cerrar
                                sesion</span>
                        </a>
                    </div>
                </div>
            </div> 
    `);

    Inicio.insertAdjacentHTML('beforeend', `
<footer class="py-3 bg-body-light>
    <div class="container-fluid clear">
        <p class="text-end text-muted">&copy; 2024 Distribuidora TMG El Salvador . Todos los derechos reservados.</p>
    </div>
</footer>
`);