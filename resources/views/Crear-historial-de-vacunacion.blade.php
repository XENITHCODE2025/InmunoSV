<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Crear Historial</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght=400;600&family=Poppins:wght=600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/historial.css') }}">
</head>
<body>
    <main class="historial-container">
        <div class="historial-card">
            
            <div class="header-section">
                <a href="{{ url('/welcome') }}" class="back-link" aria-label="Volver al inicio">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    <span>Volver</span>
                </a>
                <div class="logo-small">
                    <img src="{{ asset('img/logor.png') }}" alt="InmunoSV Logo Pequeño">
                </div>
            </div>

            <div class="title-section">
                <h1 class="form-title">Crear Historial de Vacunación</h1>
                <p class="form-subtitle">Inicia tu historial digital para llevar control de tus vacunas</p>
            </div>

            @if(session('success'))
                <div class="mensaje-resultado mensaje-success">
                    <div class="mensaje-icono">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                    </div>
                    <p class="mensaje-texto">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="mensaje-resultado mensaje-warning">
                    <div class="mensaje-icono">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <p class="mensaje-texto">{{ session('warning') }}</p>
                </div>
            @endif


            @if(isset($tieneHistorial) && $tieneHistorial)
                
                <div class="estado-historial" id="estado-historial">
                    <div class="estado-icono icono-success" id="estado-icono">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <h2 class="estado-titulo">Historial activo</h2>
                    <p class="estado-desc">Tu historial de vacunación está listo. Ya puedes registrar tus vacunas.</p>
                </div>

                <div class="acciones-historial">
                    <a href="{{ url('/mi-historial') }}" class="btn-ver">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span>Ver mi historial</span>
                    </a>
                </div>

            @else

                <div class="estado-historial" id="estado-historial">
                    <div class="estado-icono" id="estado-icono">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <line x1="10" y1="9" x2="8" y2="9"/>
                        </svg>
                    </div>
                    <h2 class="estado-titulo">No tienes un historial creado</h2>
                    <p class="estado-desc">Crea tu historial para comenzar a registrar tus vacunas de forma organizada.</p>
                </div>

                <div class="acciones-historial">
                    <form action="{{ route('historial.store') }}" method="POST" id="form-crear-historial">
                        @csrf
                        <button type="submit" class="btn-crear" id="btn-crear">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/>
                                <path d="M12 5v14"/>
                            </svg>
                            <span>Crear Historial</span>
                        </button>
                    </form>
                </div>

            @endif

            <div class="historial-footer">
                <p>Tu información está protegida y asociada a tu cuenta.</p>
            </div>
        </div>
    </main>

    <script>
        const form = document.getElementById('form-crear-historial');
        if(form) {
            form.addEventListener('submit', function() {
                const btn = document.getElementById('btn-crear');
                btn.disabled = true;
                btn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="spinner">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    <span>Creando...</span>
                `;
            });
        }
    </script>
</body>
</html>