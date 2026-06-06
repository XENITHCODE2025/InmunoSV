<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Recordatorios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/recordatorios.css') }}">
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
            <a href="{{ url('/recordatorios') }}" class="nav-item active" aria-current="page">
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
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <span class="mobile-title">Recordatorios</span>
                <div class="user-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
            </header>

            <header class="desktop-header">
                <h1 class="page-title">Recordatorios</h1>
                <p class="page-subtitle">Tus próximas vacunas y citas programadas.</p>
            </header>

            <section class="stats-section" aria-label="Resumen de recordatorios">
                <div class="stat-card stat-proximos">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">{{ $conteoProximos ?? 3 }}</span>
                        <span class="stat-label">Próximos</span>
                        <span class="stat-sublabel">en los próximos 30 días</span>
                    </div>
                </div>
                <div class="stat-card stat-pendientes">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">{{ $conteoPendientes ?? 1 }}</span>
                        <span class="stat-label">Pendientes</span>
                        <span class="stat-sublabel">sin fecha definida</span>
                    </div>
                </div>
                <div class="stat-card stat-completados">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">{{ $conteoCompletados ?? 5 }}</span>
                        <span class="stat-label">Completados</span>
                        <span class="stat-sublabel">este año</span>
                    </div>
                </div>
                <div class="stat-card stat-activos">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">{{ $conteoActivos ?? 2 }}</span>
                        <span class="stat-label">Recordatorios</span>
                        <span class="stat-sublabel">activos</span>
                    </div>
                </div>
            </section>

            <section class="tabla-section" aria-label="Próximos recordatorios">
                <h2 class="section-title">Próximos recordatorios</h2>

                <div class="tabla-desktop">
                    <table class="tabla-recordatorios">
                        <thead>
                            <tr>
                                <th>Vacuna / Cita</th>
                                <th>Fecha</th>
                                <th>Lugar</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-body">
                            @isset($recordatorios)
                                @foreach($recordatorios as $r)
                                <tr>
                                    <td>
                                        <div class="vacuna-cell">
                                            <span class="vacuna-icono">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2">
                                                    <path d="M12 22C12 22 20 18 20 12C20 6 12 2 12 2C12 2 4 6 4 12C4 18 12 22 12 22Z" stroke="{{ $r['color'] }}" fill="none"/>
                                                    <path d="M12 8V16" stroke="{{ $r['color'] }}"/>
                                                    <circle cx="12" cy="6" r="1.5" fill="{{ $r['color'] }}"/>
                                                </svg>
                                            </span>
                                            <div class="vacuna-info">
                                                <span class="vacuna-nombre">{{ $r['nombre'] }}</span>
                                                <span class="vacuna-subtitulo">{{ $r['subtitulo'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fecha-cell">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                <line x1="16" y1="2" x2="16" y2="6"/>
                                                <line x1="8" y1="2" x2="8" y2="6"/>
                                                <line x1="3" y1="10" x2="21" y2="10"/>
                                            </svg>
                                            <div class="fecha-info">
                                                <span class="fecha-valor">{{ $r['fecha'] }}</span>
                                                <span class="fecha-dias">{{ $r['dias'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="lugar-cell">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 21h18"/>
                                                <path d="M5 21V7l8-4 8 4v14"/>
                                                <path d="M9 21v-6h6v6"/>
                                            </svg>
                                            <div class="lugar-info">
                                                <span class="lugar-nombre">{{ $r['lugar'] }}</span>
                                                <span class="lugar-direccion">{{ $r['direccion'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-estado {{ $r['estado'] }}">
                                            <span class="badge-dot"></span>
                                            {{ $r['estado'] === 'proximo' ? 'Próximo' : 'Pendiente' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>

                <div class="cards-movil" id="cards-movil"></div>
            </section>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
    </script>
</body>
</html>