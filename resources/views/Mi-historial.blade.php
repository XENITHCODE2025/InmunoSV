<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Control de Vacunación</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/progreso.css') }}">
</head>

<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">
                <div class="logo-small">
                    <img src="{{ asset('img/logor.png') }}" alt="InmunoSV Logo">
                </div>
            </div>
            <span class="brand-name">Inmuno<span class="brand-highlight">SV</span></span>
        </div>

        <nav class="sidebar-nav" aria-label="Navegación principal">
            <a href="{{ url('/mi-historial') }}" class="nav-item active" aria-current="page">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <line x1="10" y1="9" x2="8" y2="9" />
                </svg>
                <span>Mi Control</span>
            </a>
            <a href="{{ url('/recordatorios') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                <span>Recordatorios</span>
            </a>
            <a href="{{ url('/mi-perfil') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span>Mi Perfil</span>
            </a>
            <a href="{{ url('/muni') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18" />
                    <path d="M5 21V7l8-4 8 4v14" />
                    <path d="M9 21v-6h6v6" />
                    <path d="M10 9h4" />
                    <path d="M10 12h4" />
                    <path d="M10 15h4" />
                </svg>
                <span>Muni</span>
            </a>
        </nav>

        <div class="sidebar-footer" id="sidebarFooter">
            <a href="{{ url('/') }}" class="nav-item logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                <span>Cerrar sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div class="page-wrapper">
        <main class="main-content">
            <header class="mobile-header">
                <button class="menu-btn" onclick="toggleSidebar()" aria-label="Abrir menú">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
                <span class="mobile-title">Control</span>
                <div class="user-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
            </header>

            <header class="desktop-header">
                <h1 class="page-title">Control de Vacunación</h1>
                <p class="page-subtitle">Historial de vacunas aplicadas</p>
            </header>

            <div class="filtros-section" aria-label="Filtros de búsqueda">
                <div class="filtros-row">
                    <div class="filtro-group">
                        <select id="filtro-tipo" class="filtro-select" onchange="aplicarFiltros()">
                            <option value="">Todas las vacunas</option>

                            @foreach($vacunas as $vacuna)
                            <option value="{{ strtolower($vacuna->nombre) }}">
                                {{ $vacuna->nombre }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="filtro-group">
                        <select id="filtro-fecha" class="filtro-select" onchange="aplicarFiltros()">
                            <option value="">Filtrar por fecha</option>
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="filtro-group filtro-busqueda">
                        <div class="busqueda-wrapper">
                            <span class="busqueda-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </span>
                            <input
                                type="text"
                                id="busqueda"
                                class="busqueda-input"
                                placeholder="Buscar..."
                                oninput="aplicarFiltros()">
                        </div>
                    </div>
                    <a href="{{ url('/Registrar-vacuna') }}" class="btn-registrar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        <span>Registrar vacuna</span>
                    </a>
                </div>
            </div>

            <section class="tabla-section" aria-label="Vacunas aplicadas">
                <div class="tabla-desktop" id="tabla-aplicadas-desktop">
                    <table class="tabla-vacunas">
                        <thead>
                            <tr>
                                <th>Vacuna</th>
                                <th>Fecha aplicada</th>
                                <th>Dosis</th>
                                <th>Lugar donde se aplicó</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tabla-aplicadas-body">
                            @isset($vacunasAplicadas)

                            <form
                                id="formEliminar"
                                method="POST"
                                style="display:none;">

                                @csrf
                                @method('DELETE')

                            </form>



                            @foreach($vacunasAplicadas as $v)
                            <tr>
                                <td>
                                    <div class="vacuna-cell">
                                        <span class="vacuna-icono">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 22C12 22 20 18 20 12C20 6 12 2 12 2C12 2 4 6 4 12C4 18 12 22 12 22Z" />
                                            </svg>
                                        </span>

                                        <span class="vacuna-nombre">
                                            {{ $v->vacuna->nombre ?? 'Vacuna no encontrada' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="fecha-aplicacion"
                                    data-year="{{ \Carbon\Carbon::parse($v->fecha_aplicacion)->format('Y') }}">
                                    {{ \Carbon\Carbon::parse($v->fecha_aplicacion)->format('d/m/Y') }}
                                </td>

                                <td>
                                    <span class="badge-dosis">
                                        {{ $v->dosis }}
                                    </span>
                                </td>

                                <td>
                                    {{ $v->lugar }}
                                </td>

                                <td style="position: relative;">

                                    <button
                                        type="button"
                                        class="btn-menu"
                                        onclick="toggleMenu({{ $v->id }})">

                                        <svg width="18" height="18"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2">

                                            <circle cx="12" cy="5" r="1" />
                                            <circle cx="12" cy="12" r="1" />
                                            <circle cx="12" cy="19" r="1" />
                                        </svg>

                                    </button>

                                    <div id="menu-{{ $v->id }}" class="dropdown-menu">
                                        <button
                                            type="button"
                                            class="btn-eliminar"
                                            onclick="confirmarEliminar({{ $v->id }})">

                                            Eliminar

                                        </button>
                                    </div>

                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>

                <div class="cards-movil" id="cards-aplicadas-movil">
                </div>

                <p class="tabla-nota">Este es el historial de vacunas que ya han sido aplicadas.</p>
            </section>

            <section class="tabla-section pendientes-section" aria-label="Vacunas pendientes">
                <h2 class="section-title">Vacunas pendientes</h2>

                <div class="tabla-desktop" id="tabla-pendientes-desktop">
                    <table class="tabla-vacunas tabla-pendientes">
                        <thead>
                            <tr>
                                <th>Vacuna</th>
                                <th>Dosis</th>
                                <th>Lugar donde se pondrá</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-pendientes-body">

                            @foreach($vacunasPendientes as $vp)

                            <tr>

                                <td>
                                    <div class="vacuna-cell">

                                        <span class="vacuna-icono">
                                            💉
                                        </span>

                                        <span class="vacuna-nombre">
                                            {{ $vp->nombre }}
                                        </span>

                                    </div>
                                </td>

                                <td>
                                    Pendiente
                                </td>

                                <td>
                                    Centro de salud
                                </td>

                                <td>
                                    <a href="{{ url('/Registrar-vacuna') }}"
                                        class="btn-marcar">
                                        Registrar
                                    </a>
                                </td>

                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="cards-movil" id="cards-pendientes-movil"></div>
                <p class="tabla-nota">Estas vacunas están pendientes y aún no han sido aplicadas.</p>
            </section>
        </main>

        <aside class="recomendaciones-panel" aria-label="Recomendaciones">
            <h2 class="recomendaciones-title">Recomendaciones</h2>

            <div class="recomendaciones-list" id="recomendaciones-list">
                @isset($vacunasRecomendadas)
                @foreach($vacunasRecomendadas as $vacuna)

                <article class="recomendacion-card">

                    <div class="recomendacion-icono">
                        @if(str_contains(strtolower($vacuna->nombre), 'influenza'))
                        🌡️
                        @elseif(str_contains(strtolower($vacuna->nombre), 'covid'))
                        🦠
                        @elseif(str_contains(strtolower($vacuna->nombre), 'hepatitis'))
                        💉
                        @elseif(str_contains(strtolower($vacuna->nombre), 'tetanos'))
                        🩹
                        @else
                        💉
                        @endif
                    </div>

                    <div class="recomendacion-content">

                        <h3 class="recomendacion-titulo">
                            {{ $vacuna->recomendacion->titulo ?? 'Recomendación' }}
                        </h3>

                        <p class="recomendacion-descripcion">
                            {{ $vacuna->recomendacion->descripcion ?? '' }}
                        </p>

                    </div>

                </article>

                @endforeach
                @endisset
            </div>
        </aside>
    </div>

    <script>
        function confirmarEliminar(id) {
            alertify.confirm(
                    'Eliminar vacuna',
                    '¿Estás seguro de que deseas eliminar este registro de vacunación?',
                    function() {

                        const form =
                            document.getElementById('formEliminar');

                        form.action = '/vacunas/' + id;

                        form.submit();
                    },
                    function() {

                        alertify.error('Operación cancelada');

                    }
                )
                .set('labels', {
                    ok: 'Eliminar',
                    cancel: 'Cancelar'
                });
        }
    </script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        function aplicarFiltros() {

            const vacunaFiltro = document
                .getElementById('filtro-tipo')
                .value
                .toLowerCase();

            const anioFiltro = document.getElementById('filtro-fecha').value;

            const busqueda = document
                .getElementById('busqueda')
                .value
                .toLowerCase();

            const filas = document.querySelectorAll('#tabla-aplicadas-body tr');

            filas.forEach(fila => {

                const vacuna = fila.cells[0].innerText.toLowerCase();
                const fecha = fila.cells[1].innerText;
                const anio = fecha.split('/')[2];

                const coincideVacuna =
                    vacunaFiltro === '' ||
                    vacuna.includes(vacunaFiltro);

                const coincideAnio =
                    anioFiltro === '' ||
                    anio === anioFiltro;

                const coincideBusqueda =
                    busqueda === '' ||
                    fila.innerText.toLowerCase().includes(busqueda);

                fila.style.display =
                    coincideVacuna &&
                    coincideAnio &&
                    coincideBusqueda ?
                    '' :
                    'none';
            });
        }
    </script>

    <script>
        function toggleMenu(id) {

            document
                .querySelectorAll('.dropdown-menu')
                .forEach(menu => {

                    if (menu.id !== 'menu-' + id) {
                        menu.style.display = 'none';
                    }

                });

            const menu = document.getElementById(
                'menu-' + id
            );

            menu.style.display =
                menu.style.display === 'block' ?
                'none' :
                'block';
        }

        function confirmarEliminar(id) {

            alertify.confirm(
                'Eliminar vacuna',
                '¿Estás seguro de que deseas eliminar este registro de vacunación?',
                function() {

                    const form =
                        document.getElementById('formEliminar');

                    form.action =
                        '/vacunas/' + id;

                    form.submit();

                },
                function() {

                    alertify.error(
                        'Operación cancelada'
                    );

                }
            ).set('labels', {
                ok: 'Eliminar',
                cancel: 'Cancelar'
            });

        }

        document.addEventListener('click', function(event) {

            if (!event.target.closest('.btn-menu')) {

                document.querySelectorAll('.dropdown-menu')
                    .forEach(menu => {
                        menu.style.display = 'none';
                    });

            }

        });
    </script>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    @if(session('success'))
    <script>
        alertify.success("{{ session('success') }}");
    </script>
    @endif
</body>

</html>