<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <main class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="{{ asset('img/logo.png') }}" alt="InmunoSV Logo">
                </div>
                <p class="brand-tagline">Salud preventiva, siempre contigo</p>
            </div>

            <form class="login-form" id="loginForm" novalidate>
                <h2 class="form-title">Iniciar Sesión</h2>
                <p class="form-subtitle">Ingresa tus datos para continuar</p>

                <div class="input-group">
                    <label for="email" class="input-label">Correo electrónico <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="ejemplo@gmail.com"
                            autocomplete="email"
                            required
                            aria-describedby="email-error"
                            maxlength="100"
                        >
                    </div>
                    <span class="error-message" id="email-error" role="alert"></span>
                </div>

                <div class="input-group">
                    <label for="password" class="input-label">Contraseña <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Tu contraseña"
                            autocomplete="current-password"
                            required
                            aria-describedby="password-error"
                            maxlength="128"
                        >
                        <button type="button" class="toggle-password" aria-label="Mostrar contraseña" onclick="togglePassword()">
                            <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eye-off-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" x2="22" y1="2" y2="22"/>
                            </svg>
                        </button>
                    </div>
                    <span class="error-message" id="password-error" role="alert"></span>
                </div>

                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="remember" class="checkbox-input" id="remember">
                        <span class="checkbox-custom" aria-hidden="true"></span>
                        <span class="checkbox-label">Recordarme</span>
                    </label>
                    <a href="{{ url('/recuperar-contrasena') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-login" id="btn-login">
                    <span id="btn-text">Iniciar Sesión</span>
                    <div class="spinner" id="btn-spinner" style="display: none;"></div>
                    <svg id="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </button>
            </form>

            <div class="login-footer">
                <p>¿No tienes cuenta? <a href="{{ url('/registro') }}" class="register-link">Regístrate aquí</a></p>
            </div>
        </div>
    </main>

    <div id="toast" class="toast hidden">
        <span id="toast-icon" class="toast-icon"></span>
        <span id="toast-message"></span>
    </div>

    <script>
        // ============================================
        // CONFIGURACIÓN GLOBAL Y PROTECCIÓN FUERZA BRUTA
        // ============================================
        let attemptCount = parseInt(sessionStorage.getItem('login_attempts') || '0');
        const MAX_ATTEMPTS = 5;
        const LOCKOUT_TIME = 15 * 60 * 1000;

        // REGEX CORREGIDA: Permite .com, .org, .edu, .sv, etc.
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const btnLogin = document.getElementById('btn-login');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        const btnIcon = document.getElementById('btn-icon');

        function checkLockout() {
            const lockoutEnd = localStorage.getItem('login_lockout_end');
            if (lockoutEnd) {
                const now = Date.now();
                if (now < parseInt(lockoutEnd)) {
                    const remaining = Math.ceil((parseInt(lockoutEnd) - now) / 60000);
                    showToast('error', 'Demasiados intentos fallidos. Espera ' + remaining + ' minutos.');
                    btnLogin.disabled = true;
                    return true;
                } else {
                    localStorage.removeItem('login_lockout_end');
                    sessionStorage.removeItem('login_attempts');
                    attemptCount = 0;
                    btnLogin.disabled = false;
                }
            }
            return false;
        }

        function recordFailedAttempt() {
            attemptCount++;
            sessionStorage.setItem('login_attempts', attemptCount.toString());

            if (attemptCount >= MAX_ATTEMPTS) {
                const lockoutEnd = Date.now() + LOCKOUT_TIME;
                localStorage.setItem('login_lockout_end', lockoutEnd.toString());
                showToast('error', 'Demasiados intentos fallidos. Tu cuenta está bloqueada por 15 minutos.');
                btnLogin.disabled = true;
            }
        }

        // ============================================
        // UTILIDADES DE INTERFAZ
        // ============================================
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        }

        function showError(fieldId, message) {
            const errorEl = document.getElementById(fieldId + '-error');
            const inputEl = document.getElementById(fieldId);
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.style.display = 'block';
            }
            if (inputEl) {
                inputEl.classList.add('input-error');
                inputEl.setAttribute('aria-invalid', 'true');
            }
        }

        function clearError(fieldId) {
            const errorEl = document.getElementById(fieldId + '-error');
            const inputEl = document.getElementById(fieldId);
            if (errorEl) {
                errorEl.textContent = '';
                errorEl.style.display = 'none';
            }
            if (inputEl) {
                inputEl.classList.remove('input-error');
                inputEl.removeAttribute('aria-invalid');
            }
        }

        function showToast(type, message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');

            if (!toast || !toastMessage) {
                alert(message);
                return;
            }

            toastMessage.textContent = message;
            toast.className = 'toast toast-' + type;
            void toast.offsetWidth; // Force reflow
            toast.classList.add('show');

            if (toast._timeout) clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }

        // ============================================
        // VALIDADORES INDIVIDUALES
        // ============================================
        function validateEmail() {
            const value = emailInput.value.trim();
            clearError('email');

            if (!value) {
                showError('email', 'El correo electrónico es obligatorio');
                return false;
            }
            if (value.length > 100) {
                showError('email', 'El correo no puede exceder 100 caracteres');
                return false;
            }
            if (/\s/.test(value)) {
                showError('email', 'El correo no puede contener espacios');
                return false;
            }
            if (!emailRegex.test(value)) {
                showError('email', 'Ingrese un formato de correo electrónico válido.');
                return false;
            }
            return true;
        }

        function validatePassword() {
            const value = passwordInput.value;
            clearError('password');

            if (!value) {
                showError('password', 'La contraseña es obligatoria');
                return false;
            }
            if (value.length < 8) {
                showError('password', 'La contraseña debe tener al menos 8 caracteres');
                return false;
            }
            if (value.length > 128) {
                showError('password', 'La contraseña no puede exceder 128 caracteres');
                return false;
            }
            if (!/[A-Z]/.test(value) || !/[a-z]/.test(value) || !/[0-9]/.test(value) || !/[!@#$%^&*()_+\-={}\[\]:;"'|<>,.?/~`]/.test(value)) {
                showError('password', 'La contraseña no cumple con los requisitos de complejidad.');
                return false;
            }
            return true;
        }

        // Listeners en tiempo real
        emailInput.addEventListener('input', function() { if (this.value.trim()) validateEmail(); else clearError('email'); });
        passwordInput.addEventListener('input', function() { if (this.value) validatePassword(); else clearError('password'); });
        emailInput.addEventListener('paste', function() { setTimeout(() => { this.value = this.value.trim().replace(/\s/g, ''); validateEmail(); }, 0); });

        function setLoading(loading) {
            if (loading) {
                btnLogin.disabled = true;
                btnText.textContent = 'Iniciando sesión...';
                btnSpinner.style.display = 'inline-block';
                btnIcon.style.display = 'none';
            } else {
                btnLogin.disabled = false;
                btnText.textContent = 'Iniciar Sesión';
                btnSpinner.style.display = 'none';
                btnIcon.style.display = 'inline-block';
            }
        }

        // Enviar Formulario con redirección limpia de Laravel
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (checkLockout()) return;

            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();

            if (!isEmailValid || !isPasswordValid) {
                if (!isEmailValid) emailInput.focus();
                else passwordInput.focus();
                return;
            }

            setLoading(true);

            // Simulación de respuesta exitosa conectada a las rutas reales de Laravel
            setTimeout(() => {
                setLoading(false);
                showToast('success', '¡Inicio de sesión exitoso! Redirigiendo...');
                
                setTimeout(() => {
                    // Redirección dinámica limpia al ruteo de Blade
                    window.location.href = "{{ url('/crear-historial') }}";
                }, 1000);
            }, 1200);
        });

        // Inicializar revisión de bloqueo al cargar
        checkLockout();
    </script>
</body>
</html>