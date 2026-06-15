<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Mi Perfil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
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
            <a href="{{ url('/mi-perfil') }}" class="nav-item active" aria-current="page">
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

    <main class="main-content">
        <header class="mobile-header">
            <button class="menu-btn" onclick="toggleSidebar()" aria-label="Abrir menú">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <span class="mobile-title">Mi Perfil</span>
            <div class="user-avatar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
        </header>

        <header class="desktop-header">
            <h1 class="page-title">Mi Perfil</h1>
            <p class="page-subtitle">Información personal</p>
        </header>

        <div class="perfil-content">
            <div class="avatar-card">
                <div class="avatar-wrapper">

    <div class="avatar-large">

        @if(auth()->user()->foto)

            <img
                src="{{ asset(auth()->user()->foto) }}"
                alt="Foto de perfil"
                class="foto-perfil">

        @else

            <svg width="64" height="64" viewBox="0 0 24 24"
                 fill="none"
                 stroke="#3933B2"
                 stroke-width="1.5"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>

            </svg>

        @endif

    </div>

    <form id="formFoto"
          action="{{ route('perfil.foto') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <input
            type="file"
            name="foto"
            id="inputFoto"
            accept="image/*"
            style="display:none;">
    </form>

    <button type="button"
            class="btn-camara"
            aria-label="Cambiar foto de perfil">

        <svg width="16"
             height="16"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2">

            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
            <circle cx="12" cy="13" r="4"/>

        </svg>

    </button>

</div>
            </div>

            <div class="info-card">
                <div class="info-list">
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <span class="info-label">Nombre completo</span>
                        <span class="info-value">{{ auth()->user()->name ?? 'María José Hernández' }}</span>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </div>
                        <span class="info-label">Correo electrónico</span>
                        <span class="info-value">{{ auth()->user()->email ?? 'mariajose.hdez@gmail.com' }}</span>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </div>
                        <span class="info-label">Teléfono</span>
                        <span class="info-value">{{ auth()->user()->telefono ?? '+503 7123 4567' }}</span>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <span class="info-label">Fecha de nacimiento</span>
                        <span class="info-value">
                            {{ isset(auth()->user()->fecha_nacimiento) ? \Carbon\Carbon::parse(auth()->user()->fecha_nacimiento)->translatedFormat('d \d\e F \d\e Y') : '15 de marzo de 1998' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <span class="info-label">Edad</span>
                        <span class="info-value">
                            {{ isset(auth()->user()->fecha_nacimiento) ? \Carbon\Carbon::parse(auth()->user()->fecha_nacimiento)->age . ' años' : '27 años' }}
                        </span>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <span class="info-label">Dirección</span>
                        <span class="info-value">{{ auth()->user()->direccion ?? 'Calle El Sauce, #123, Colonia Escalón, San Salvador, El Salvador' }}</span>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 2a7 7 0 0 0-7 7c0 2.38 1.19 4.47 3 5.74V17a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-2.26c1.81-1.27 3-3.36 3-5.74a7 7 0 0 0-7-7z"/>
                            </svg>
                        </div>
                        <span class="info-label">Género</span>
                        <span class="info-value">{{ auth()->user()->genero ?? 'Femenino' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
}

document.addEventListener('DOMContentLoaded', function() {

    const btnCamara = document.querySelector('.btn-camara');
    const inputFoto = document.getElementById('inputFoto');

    if(btnCamara && inputFoto){

        btnCamara.addEventListener('click', function() {
            inputFoto.click();
        });

        inputFoto.addEventListener('change', function() {

            if(this.files.length > 0){
                document.getElementById('formFoto').submit();
            }

        });

    }

});
    </script>
    
</body>
</html>