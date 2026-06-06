<!DOCTYPE html>
<html lang="es">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Registrar Vacuna</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/vacunacion.css') }}">
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
            <a href="{{ url('/mi-historial') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <line x1="10" y1="9" x2="8" y2="9"/>
                </svg>
                <span>Mi Control</span>
            </a>
            <a href="{{ url('/recordatorios') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span>Recordatorios</span>
            </a>
            <a href="{{ url('/mi-perfil') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span>Mi Perfil</span>
            </a>
            <a href="{{ url('/muni') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18"/>
                    <path d="M5 21V7l8-4 8 4v14"/>
                    <path d="M9 21v-6h6v6"/>
                    <path d="M10 9h4"/>
                    <path d="M10 12h4"/>
                    <path d="M10 15h4"/>
                </svg>
                <span>Muni</span>
            </a>
        </nav>

        <div class="sidebar-footer" id="sidebarFooter">
            <a href="{{ url('/') }}" class="nav-item logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Cerrar Sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    <main class="main-content">
        <header class="mobile-header">
            <button class="menu-btn" onclick="toggleSidebar()" aria-label="Abrir menú">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <span class="mobile-title">Registrar Vacuna</span>
            <div class="user-avatar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
        </header>

        <header class="desktop-header">
            <h1 class="page-title">Registrar nueva vacuna</h1>
            <p class="page-subtitle">Completa los datos de la vacuna recibida</p>
        </header>

        <section class="form-section">
            <form action="{{ route('vacunas.store') }}" method="POST" class="vacuna-form" id="vacunaForm" novalidate>
                @csrf

                <div class="form-row">
                    <div class="input-group">
                        <label for="nombre-vacuna" class="input-label">
                            Nombre de la vacuna
                            <span class="required-badge" aria-label="Campo obligatorio">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <select 
                                id="nombre-vacuna" 
                                name="nombre" 
                                class="form-input form-select" 
                                required
                                aria-describedby="nombre-error"
                            >
                                <option value="" disabled selected>Selecciona la vacuna</option>
                                <optgroup label="Vacunas del Esquema Nacional (Gratuitas)">
                                    <option value="influenza-tetravalente">Influenza Tetravalente (o Influenza Hemisferio Sur)</option>
                                    <option value="neumococo-23">Neumococo 23-Valente (Pneumo 23)</option>
                                    <option value="td">Td (Tétanos y Difteria)</option>
                                </optgroup>
                                <optgroup label="Vacunas de Campaña y Grupos de Riesgo">
                                    <option value="covid-19">SARS-CoV-2 (COVID-19)</option>
                                    <option value="hepatitis-b">Hepatitis B (HB)</option>
                                    <option value="sr">SR (Sarampión y Rubéola)</option>
                                </optgroup>
                                <optgroup label="Vacunas Recomendadas (Sector Privado)">
                                    <option value="herpes-zoster">Herpes Zóster (Culebrilla)</option>
                                    <option value="vrs">VRS (Virus Respiratorio Sincitial)</option>
                                </optgroup>
                            </select>
                            <span class="select-arrow" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </span>
                        </div>
                        <span class="error-message" id="nombre-error" role="alert"></span>
                    </div>

                    <div class="input-group">
                        <label for="tipo-vacuna" class="input-label">Tipo de vacuna</label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 7V4h3"/>
                                    <path d="M4 17v3h3"/>
                                    <path d="M20 7V4h-3"/>
                                    <path d="M20 17v3h-3"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </span>
                            <select id="tipo-vacuna" name="tipo" class="form-input form-select">
                                <option value="" disabled selected>Selecciona el tipo</option>
                                <option value="preventiva">Preventiva</option>
                                <option value="obligatoria">Obligatoria</option>
                                <option value="refuerzo">Refuerzo</option>
                                <option value="covid">COVID-19</option>
                                <option value="gripe">Gripe / Influenza</option>
                                <option value="otra">Otra</option>
                            </select>
                            <span class="select-arrow" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group">
                        <label for="fecha-aplicacion" class="input-label">
                            Fecha de aplicación
                            <span class="required-badge" aria-label="Campo obligatorio">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                            </span>
                            <input 
                                type="date" 
                                id="fecha-aplicacion" 
                                name="fecha" 
                                class="form-input"
                                required
                                max=""
                                aria-describedby="fecha-error"
                            >
                        </div>
                        <span class="error-message" id="fecha-error" role="alert"></span>
                        <span class="help-text">La fecha no puede ser futura</span>
                    </div>

                    <div class="input-group">
                        <label for="dosis" class="input-label">
                            Dosis
                            <span class="required-badge" aria-label="Campo obligatorio">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 7h-9"/>
                                    <path d="M14 17H5"/>
                                    <circle cx="17" cy="17" r="3"/>
                                    <circle cx="7" cy="7" r="3"/>
                                </svg>
                            </span>
                            <select id="dosis" name="dosis" class="form-input form-select" required>
                                <option value="" disabled selected>Selecciona la dosis</option>
                                <option value="1ra">1ra dosis</option>
                                <option value="2da">2da dosis</option>
                                <option value="3ra">3ra dosis</option>
                                <option value="4ta">4ta dosis</option>
                                <option value="refuerzo">Refuerzo</option>
                                <option value="unica">Dosis única</option>
                            </select>
                            <span class="select-arrow" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </span>
                        </div>
                        <span class="error-message" id="dosis-error" role="alert"></span>
                    </div>
                </div>

                <div class="input-group input-full">
                    <label for="lugar" class="input-label">Lugar de aplicación</label>
                    <div class="input-wrapper">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            id="lugar" 
                            name="lugar" 
                            class="form-input" 
                            placeholder="Ej: Hospital Nacional de San Miguel"
                        >
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="window.location.href='{{ url('/mi-historial') }}'">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 19-7-7 7-7"/>
                            <path d="M19 12H5"/>
                        </svg>
                        <span>Cancelar</span>
                    </button>
                    <button type="submit" class="btn-save">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        <span>Guardar</span>
                    </button>
                </div>
            </form>
        </section>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        document.getElementById('fecha-aplicacion').max = new Date().toISOString().split('T')[0];

        const form = document.getElementById('vacunaForm');

        form.addEventListener('submit', function(e) {
            let isValid = true;

            const nombre = document.getElementById('nombre-vacuna');
            const nombreError = document.getElementById('nombre-error');
            if (!nombre.value.trim()) {
                nombreError.textContent = 'El nombre de la vacuna es obligatorio';
                nombre.classList.add('input-error');
                isValid = false;
            } else {
                nombreError.textContent = '';
                nombre.classList.remove('input-error');
            }

            const fecha = document.getElementById('fecha-aplicacion');
            const fechaError = document.getElementById('fecha-error');
            const fechaValue = new Date(fecha.value);
            const hoy = new Date();
            hoy.setHours(23, 59, 59, 999);

            if (!fecha.value) {
                fechaError.textContent = 'La fecha de aplicación es obligatoria';
                fecha.classList.add('input-error');
                isValid = false;
            } else if (fechaValue > hoy) {
                fechaError.textContent = 'La fecha no puede ser futura';
                fecha.classList.add('input-error');
                isValid = false;
            } else {
                fechaError.textContent = '';
                fecha.classList.remove('input-error');
            }

            const dosis = document.getElementById('dosis');
            const dosisError = document.getElementById('dosis-error');
            if (!dosis.value) {
                dosisError.textContent = 'Debes seleccionar la dosis';
                dosis.classList.add('input-error');
                isValid = false;
            } else {
                dosisError.textContent = '';
                dosis.classList.remove('input-error');
            } 

            if (!isValid) {
                e.preventDefault(); // Detiene el envío real si hay fallas en la maquetación
            }
        });

        // Limpieza de errores en tiempo real adaptada
        document.getElementById('nombre-vacuna').addEventListener('change', function() {
            this.classList.remove('input-error');
            document.getElementById('nombre-error').textContent = '';
        });

        document.getElementById('fecha-aplicacion').addEventListener('input', function() {
            this.classList.remove('input-error');
            document.getElementById('fecha-error').textContent = '';
        });

        document.getElementById('dosis').addEventListener('change', function() {
            this.classList.remove('input-error');
            document.getElementById('dosis-error').textContent = '';
        });
    </script>
</body>
</html>