<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Formulario Enviado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/notificacion.css') }}">
</head>
<body>
    <div class="modal">
        <button class="btn-close" onclick="window.location.href='{{ url('/') }}'" aria-label="Cerrar">
            <img src="{{ asset('img/icons/x.png') }}" alt="Cerrar">
        </button>

        <div class="success-circle">
            <img src="{{ asset('img/icons/marca-de-verificacion.png') }}" alt="Formulario enviado correctamente">
        </div>

        <h1 class="modal-title">¡Formulario enviado!</h1>

        <p class="modal-subtitle">Hemos recibido tu información correctamente.<br>Pronto generaremos tus recomendaciones personalizadas.</p>

        <div class="notice-box">
            <span class="notice-icon">
                <img src="{{ asset('img/icons/notificacion.png') }}" alt="Notificación">
            </span>
            <span class="notice-text">
                <strong>Te notificaremos cuando tus recomendaciones estén listas.</strong>
                Esto puede tomar unos minutos.
            </span>
        </div>

        <a href="{{ url('/') }}" class="btn-primary">
            <img src="{{ asset('img/icons/hogar.png') }}" alt="Inicio">
            Ir a pantalla principal
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loggedEmail = sessionStorage.getItem('inmunosv_logged_user');
            if (loggedEmail) {
                const users = JSON.parse(localStorage.getItem('inmunosv_users') || '[]');
                const userIndex = users.findIndex(u => u.email === loggedEmail);
                if (userIndex !== -1) {
                    if (!users[userIndex].perfilSalud) {
                        users[userIndex].perfilSalud = {};
                    }
                    users[userIndex].perfilSalud.formularioCompletado = true;
                    localStorage.setItem('inmunosv_users', JSON.stringify(users));
                }
            }
        });
    </script>
</body>
</html>