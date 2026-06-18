<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV - Inicio</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- CSS enlazado correctamente con Laravel -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar">
        <!-- Logo horizontal blanco/transparente -->
        <img src="{{ asset('img/logo-horinzontal.png') }}" alt="InmunoSV Logo" class="nav-logo-img">
        
        <ul class="nav-links">
            <li><a href="#quienes">Quienes Somos</a></li>
            <li><a href="#servicios">Servicios</a></li>
        </ul>

        <div class="nav-buttons">
            <!-- REDIRECCIÓN CORREGIDA: Apunta a la ruta real /login de Laravel -->
            <button class="btn-login" onclick="location.href='{{ url('/login') }}'">
                <!-- Icono usuario login -->
                <img src="{{ asset('img/icons/user-login.png') }}" alt="Login">
                Iniciar Sesión
            </button>
            <!-- REDIRECCIÓN CORREGIDA: Apunta a la ruta real /registro de Laravel -->
            <button class="btn-register" onclick="location.href='{{ url('/registro') }}'">Registrarme</button>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="hero">
        <img src="{{ asset('img/laboratorio.jpg') }}" alt="Laboratorio" class="hero-img">
        <div class="hero-content">
            <h1>INMUNOSV</h1>
            <p>Gestión digital para una<br>salud preventiva más<br>eficiente.</p>
        </div>
    </section>

    <!-- ===== QUIENES SOMOS ===== -->
    <section class="quienes-somos" id="quienes">
        <h2>QUIENES SOMOS</h2>
        
        <div class="quienes-container">
            <div class="quienes-text">
                <p>InmunoSV es una aplicación web enfocada en la gestión de la salud preventiva en adultos mayores, permitiendo llevar un control de vacunas, recordatorios personalizados y acceso a recomendaciones de salud de forma sencilla, clara y accesible.</p>
            </div>
            <div class="quienes-logo-big">
                <!-- Logo principal vertical (escudo + texto) -->
                <img src="{{ asset('img/logo.png') }}" alt="InmunoSV Logo Grande">
            </div>
        </div>

        <!-- Tarjetas Misión, Visión, Valores -->
        <div class="mvv-cards">
            <div class="mvv-card mision">
                <div class="mvv-icon">
                    <!-- Icono diana azul -->
                    <img src="{{ asset('img/icons/flecha.png') }}" alt="Misión">
                </div>
                <h3>MISION</h3>
                <p>Brindar una solución digital accesible y confiable que permita a los adultos mayores gestionar su salud preventiva de manera sencilla, mediante el control de vacunas, recordatorios personalizados e información clara que contribuya a mejorar su bienestar y calidad de vida.</p>
            </div>

            <div class="mvv-card vision">
                <div class="mvv-icon">
                    <!-- Icono ojo verde -->
                    <img src="{{ asset('img/icons/ojo.png') }}" alt="Visión">
                </div>
                <h3>VISION</h3>
                <p>Ser una plataforma referente en salud preventiva digital para adultos mayores en El Salvador, destacándose por su accesibilidad, innovación y capacidad de mejorar la calidad de vida de los usuarios a través de la tecnología.</p>
            </div>

            <div class="mvv-card valores">
                <div class="mvv-icon">
                    <!-- Icono balanza dorada -->
                    <img src="{{ asset('img/icons/vasvula.png') }}" alt="Valores">
                </div>
                <h3>VALORES</h3>
                <ul>
                    <li>Confianza</li>
                    <li>Cuidado</li>
                    <li>Cercanía</li>
                    <li>Accesibilidad</li>
                    <li>Responsabilidad</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ===== SERVICIOS ===== -->
    <section class="servicios" id="servicios">
        <h2>SERVICIOS</h2>
        <div class="servicios-grid">
            
            <div class="servicio-item">
                <h3>Control de Vacunas</h3>
                <div class="servicio-icon-circle">
                    <!-- Icono jeringa -->
                    <img src="{{ asset('img/icons/vacuna-1.png') }}" alt="Control de Vacunas">
                </div>
                <p>Mantén un registro completo y organizado de todas las vacunas aplicadas, pendientes y próximas de manera rápida y segura.</p>
            </div>

            <div class="servicio-item">
                <h3>Recordatorios</h3>
                <div class="servicio-icon-circle">
                    <!-- Icono campana -->
                    <img src="{{ asset('img/icons/notificacion-2.png') }}" alt="Recordatorios">
                </div>
                <p>Recibe notificaciones automáticas sobre próximas vacunas y citas importantes para no olvidar ningún seguimiento preventivo.</p>
            </div>

            <div class="servicio-item">
                <h3>Historial</h3>
                <div class="servicio-icon-circle">
                    <!-- Icono portapapeles médico -->
                    <img src="{{ asset('img/icons/informe-medico.png') }}" alt="Historial">
                </div>
                <p>Consulta fácilmente tu historial de vacunación y seguimiento de salud en cualquier momento y desde cualquier dispositivo.</p>
            </div>

            <div class="servicio-item">
                <h3>Chat Asistente</h3>
                <div class="servicio-icon-circle">
                    <!-- Icono robot chat -->
                    <img src="{{ asset('img/icons/chatbot.png') }}" alt="Chat Asistente">
                </div>
                <p>Obtén ayuda rápida y orientación personalizada mediante un sistema de chat accesible y fácil de usar.</p>
            </div>
            
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="footer-logo">
            <!-- Logo escudo pequeño blanco -->
            <img src="{{ asset('img/logo-blanco.png') }}" alt="InmunoSV">
        </div>
        <div class="footer-email">
            <!-- Icono sobre correo blanco -->
            <img src="{{ asset('img/icons/email.png') }}" alt="Email">
            <span>inmunosv@gmail.com</span>
        </div>
        <div class="footer-country">EL SALVADOR</div>
    </footer>

</body>
</html>