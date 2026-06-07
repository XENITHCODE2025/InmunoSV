<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Crear Cuenta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
</head>
<body>
    <main class="register-container">
        <div class="register-card">
            <div class="header-section">
                <a href="{{ url('/login') }}" class="back-link" aria-label="Volver al inicio de sesión">
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
                <h1 class="form-title">Crear Cuenta</h1>
                <p class="form-subtitle">Completa tus datos para comenzar a cuidar tu salud</p>
            </div>

            <div class="steps-indicator" aria-label="Progreso del registro">
                <div class="step active" data-step="1">
                    <div class="step-circle">1</div>
                    <span class="step-label">Datos personales</span>
                </div>
                <div class="step-connector"></div>
                <div class="step" data-step="2">
                    <div class="step-circle">2</div>
                    <span class="step-label">Contacto</span>
                </div>
                <div class="step-connector"></div>
                <div class="step" data-step="3">
                    <div class="step-circle">3</div>
                    <span class="step-label">Seguridad</span>
                </div>
            </div>

            <form action="{{ url('/registro') }}" method="POST" class="register-form" id="registerForm" novalidate>
                @csrf

                <fieldset class="form-step" id="step-1">
                    <legend class="sr-only">Paso 1: Datos personales</legend>

                    <div class="input-row">
                        <div class="input-group">
                            <label for="nombre" class="input-label">Nombres <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <span class="input-icon" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </span>
                                <input 
                                    type="text" 
                                    id="nombre" 
                                    name="name" 
                                    class="form-input" 
                                    placeholder="Ej: María Elena"
                                    autocomplete="given-name"
                                    required
                                >
                            </div>
                            <span class="error-message" id="error-nombre"></span>
                        </div>

                        <div class="input-group">
                            <label for="apellido" class="input-label">Apellidos <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <span class="input-icon" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </span>
                                <input 
                                    type="text" 
                                    id="apellido" 
                                    name="apellido" 
                                    class="form-input" 
                                    placeholder="Ej: Rodríguez López"
                                    autocomplete="family-name"
                                    required
                                >
                            </div>
                            <span class="error-message" id="error-apellido"></span>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="fecha-nacimiento" class="input-label">Fecha de nacimiento <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                            </span>
                            <input 
                                type="date" 
                                id="fecha-nacimiento" 
                                name="fecha_nacimiento" 
                                class="form-input"
                                required
                            >
                        </div>
                        <span class="help-text" id="fecha-help">Debes ser mayor de 18 años</span>
                        <span class="error-message" id="error-fecha-nacimiento"></span>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Género <span class="optional">(opcional)</span></label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="genero" value="femenino" class="radio-input">
                                <span class="radio-custom" aria-hidden="true"></span>
                                <span class="radio-label">Femenino</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="genero" value="masculino" class="radio-input">
                                <span class="radio-custom" aria-hidden="true"></span>
                                <span class="radio-label">Masculino</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="genero" value="otro" class="radio-input">
                                <span class="radio-custom" aria-hidden="true"></span>
                                <span class="radio-label">Otro</span>
                            </label>
                        </div>
                    </div>

                    <button type="button" class="btn-next" onclick="nextStep(2)">
                        <span>Continuar</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </button>
                </fieldset>

                <fieldset class="form-step hidden" id="step-2">
                    <legend class="sr-only">Paso 2: Información de contacto</legend>

                    <div class="input-group">
                        <label for="telefono" class="input-label">Teléfono <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </span>
                            <input 
                                type="tel" 
                                id="telefono" 
                                name="telefono" 
                                class="form-input" 
                                placeholder="0000-0000"
                                autocomplete="tel"
                                maxlength="9"
                                required
                            >
                        </div>
                        <span class="error-message" id="error-telefono"></span>
                    </div>

                    <div class="input-group">
                        <label for="email" class="input-label">Correo electrónico <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                            >
                        </div>
                        <span class="error-message" id="error-email"></span>
                    </div>

                    <div class="input-group">
                        <label for="departamento" class="input-label">Departamento <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </span>
                            <select id="departamento" name="departamento" class="form-input form-select" required>
                                <option value="" disabled selected>Selecciona tu departamento</option>
                                <option value="ahuachapan">Ahuachapán</option>
                                <option value="santa-ana">Santa Ana</option>
                                <option value="sonsonate">Sonsonate</option>
                                <option value="chalatenango">Chalatenango</option>
                                <option value="la-libertad">La Libertad</option>
                                <option value="san-salvador">San Salvador</option>
                                <option value="cuscatlan">Cuscatlán</option>
                                <option value="la-paz">La Paz</option>
                                <option value="cabanas">Cabañas</option>
                                <option value="san-vicente">San Vicente</option>
                                <option value="usulutan">Usulután</option>
                                <option value="san-miguel">San Miguel</option>
                                <option value="morazan">Morazán</option>
                                <option value="la-union">La Unión</option>
                            </select>
                            <span class="select-arrow" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </span>
                        </div>
                        <span class="error-message" id="error-departamento"></span>
                    </div>

                    <div class="button-row">
                        <button type="button" class="btn-back" onclick="prevStep(1)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m12 19-7-7 7-7"/>
                                <path d="M19 12H5"/>
                            </svg>
                            <span>Atrás</span>
                        </button>
                        <button type="button" class="btn-next" onclick="nextStep(3)">
                            <span>Continuar</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14"/>
                                <path d="m12 5 7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </fieldset>

                <fieldset class="form-step hidden" id="step-3">
                    <legend class="sr-only">Paso 3: Seguridad de la cuenta</legend>

                    <div class="input-group">
                        <label for="password" class="input-label">Crear contraseña <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="Mínimo 8 caracteres"
                                autocomplete="new-password"
                                required
                            >
                            <button type="button" class="toggle-password" aria-label="Mostrar contraseña" onclick="togglePassword('password', 'eye-1', 'eye-off-1')">
                                <svg id="eye-1" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg id="eye-off-1" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                    <line x1="2" x2="22" y1="2" y2="22"/>
                                </svg>
                            </button>
                        </div>
                        <span class="error-message" id="error-password"></span>

                        <div class="password-requirements" id="password-requirements">
                            <p class="requirements-title">La contraseña debe contener:</p>
                            <ul class="requirements-list">
                                <li id="req-length" class="req-item"><span class="req-icon">○</span> Mínimo 8 caracteres</li>
                                <li id="req-upper" class="req-item"><span class="req-icon">○</span> Al menos una mayúscula</li>
                                <li id="req-lower" class="req-item"><span class="req-icon">○</span> Al menos una minúscula</li>
                                <li id="req-number" class="req-item"><span class="req-icon">○</span> Al menos un número</li>
                                <li id="req-symbol" class="req-item"><span class="req-icon">○</span> Al menos un símbolo (!@#$%^&*)</li>
                            </ul>
                        </div>

                        <div class="password-strength" id="password-strength" aria-live="polite">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                            <span class="strength-text" id="strength-text">Escribe una contraseña segura</span>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="confirm-password" class="input-label">Confirmar contraseña <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <input 
                                type="password" 
                                id="confirm-password" 
                                name="password_confirmation" 
                                class="form-input" 
                                placeholder="Repite tu contraseña"
                                autocomplete="new-password"
                                required
                            >
                            <button type="button" class="toggle-password" aria-label="Mostrar contraseña" onclick="togglePassword('confirm-password', 'eye-2', 'eye-off-2')">
                                <svg id="eye-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg id="eye-off-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                    <line x1="2" x2="22" y1="2" y2="22"/>
                                </svg>
                            </button>
                        </div>
                        <span class="error-message" id="error-confirm-password"></span>
                    </div>

                    <div class="terms-section">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="terminos" id="terminos" class="checkbox-input" required>
                            <span class="checkbox-custom" aria-hidden="true"></span>
                            <span class="checkbox-label">Acepto los <a href="#" class="terms-link">términos y condiciones</a> y la <a href="#" class="terms-link">política de privacidad</a></span>
                        </label>
                        <span class="error-message" id="error-terminos"></span>
                    </div>

                    <div class="button-row">
                        <button type="button" class="btn-back" onclick="prevStep(2)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m12 19-7-7 7-7"/>
                                <path d="M19 12H5"/>
                            </svg>
                            <span>Atrás</span>
                        </button>
                        <button type="submit" class="btn-register">
                            <span>Crear Cuenta</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </button>
                    </div>
                </fieldset>
            </form>

            <div class="register-footer">
                <p>¿Ya tienes cuenta? <a href="{{ url('/login') }}" class="login-link">Inicia sesión aquí</a></p>
            </div>
        </div>
    </main>

    <div id="toast" class="toast hidden">
        <span id="toast-icon" class="toast-icon"></span>
        <span id="toast-message"></span>
    </div>

    <script>
        let currentStep = 1;

        // REGEX ACTUALIZADA: Formato flexible que acepta cualquier extensión de dominio válida (.com, .sv, etc.)
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const nombreRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/;
        const telefonoRegex = /^[0-9]{4}-[0-9]{4}$/;

        function showError(fieldId, message) {
            const errorEl = document.getElementById('error-' + fieldId);
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
            const errorEl = document.getElementById('error-' + fieldId);
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

        function clearAllErrors(stepNum) {
            const step = document.getElementById('step-' + stepNum);
            const errorMessages = step.querySelectorAll('.error-message');
            const inputs = step.querySelectorAll('.form-input, .form-select');
            errorMessages.forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
            inputs.forEach(el => {
                el.classList.remove('input-error');
                el.removeAttribute('aria-invalid');
            });
        }

        function validateStep1() {
            let isValid = true;
            clearAllErrors(1);

            const nombre = document.getElementById('nombre').value.trim();
            const apellido = document.getElementById('apellido').value.trim();
            const fechaNacimiento = document.getElementById('fecha-nacimiento').value;

            if (!nombre) { showError('nombre', 'El nombre es obligatorio'); isValid = false; }
            else if (!nombreRegex.test(nombre)) { showError('nombre', 'El nombre solo puede contener letras (mínimo 2 caracteres)'); isValid = false; }

            if (!apellido) { showError('apellido', 'El apellido es obligatorio'); isValid = false; }
            else if (!nombreRegex.test(apellido)) { showError('apellido', 'El apellido solo puede contener letras (mínimo 2 caracteres)'); isValid = false; }

            if (!fechaNacimiento) {
                showError('fecha-nacimiento', 'La fecha de nacimiento es obligatoria');
                isValid = false;
            } else {
                const fecha = new Date(fechaNacimiento);
                const hoy = new Date();
                if (fecha > hoy) {
                    showError('fecha-nacimiento', 'La fecha de nacimiento no puede ser futura');
                    isValid = false;
                } else {
                    let edad = hoy.getFullYear() - fecha.getFullYear();
                    const mes = hoy.getMonth() - fecha.getMonth();
                    if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) { edad--; }
                    if (edad < 18) {
                        showError('fecha-nacimiento', 'Debes ser mayor de 18 años para registrarte');
                        isValid = false;
                    }
                }
            }
            return isValid;
        }

        function validateStep2() {
            let isValid = true;
            clearAllErrors(2);

            const telefono = document.getElementById('telefono').value.trim();
            const email = document.getElementById('email').value.trim();
            const departamento = document.getElementById('departamento').value;

            if (!telefono) { showError('telefono', 'El teléfono es obligatorio'); isValid = false; }
            else if (!telefonoRegex.test(telefono)) { showError('telefono', 'Formato inválido. Usa: 0000-0000'); isValid = false; }

            if (!email) { showError('email', 'El correo electrónico es obligatorio'); isValid = false; }
            else if (!emailRegex.test(email)) { showError('email', 'Ingrese un formato de correo electrónico válido.'); isValid = false; }

            if (!departamento) { showError('departamento', 'Selecciona un departamento'); isValid = false; }

            return isValid;
        }

        function validateStep3() {
            let isValid = true;
            clearAllErrors(3);

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const terminos = document.getElementById('terminos').checked;

            if (!password) { showError('password', 'La contraseña es obligatoria'); isValid = false; }
            else if (password.length < 8 || !/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password) || !/[!@#$%^&*()_+\-={}\[\]:;"'|<>,.?/~`]/.test(password)) {
                showError('password', 'La contraseña no cumple los requisitos de seguridad.');
                isValid = false;
            }

            if (!confirmPassword) { showError('confirm-password', 'Confirma tu contraseña'); isValid = false; }
            else if (password !== confirmPassword) { showError('confirm-password', 'Las contraseñas NO coinciden'); isValid = false; }

            if (!terminos) {
                const errorTerminos = document.getElementById('error-terminos');
                if (errorTerminos) {
                    errorTerminos.textContent = 'Debes aceptar los términos y condiciones';
                    errorTerminos.style.display = 'block';
                }
                isValid = false;
            }
            return isValid;
        }

        document.querySelectorAll('.form-input, .form-select').forEach(input => {
            input.addEventListener('input', function() { clearError(this.id); });
            input.addEventListener('change', function() { clearError(this.id); });
        });

        document.getElementById('terminos').addEventListener('change', function() {
            const errorTerminos = document.getElementById('error-terminos');
            if (this.checked && errorTerminos) {
                errorTerminos.textContent = '';
                errorTerminos.style.display = 'none';
            }
        });

        function nextStep(step) {
            let isValid = false;
            if (currentStep === 1) isValid = validateStep1();
            else if (currentStep === 2) isValid = validateStep2();

            if (!isValid) return;

            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            document.getElementById(`step-${step}`).classList.remove('hidden');
            updateSteps(step);
            currentStep = step;
        }

        function prevStep(step) {
            clearAllErrors(currentStep);
            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            document.getElementById(`step-${step}`).classList.remove('hidden');
            updateSteps(step);
            currentStep = step;
        }

        function updateSteps(activeStep) {
            document.querySelectorAll('.step').forEach(step => {
                const stepNum = parseInt(step.dataset.step);
                step.classList.remove('active', 'completed');
                if (stepNum === activeStep) { step.classList.add('active'); }
                else if (stepNum < activeStep) { step.classList.add('completed'); }
            });
        }

        const form = document.getElementById('registerForm');

         form.addEventListener('submit', function(e) {
           if (!validateStep3()) {
           e.preventDefault();
        }
        });  

        function showToast(type, message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            if (!toast || !toastMessage) return;

            toastMessage.textContent = message;
            toast.className = 'toast toast-' + type;
            void toast.offsetWidth;
            toast.classList.add('show');
        }

        function togglePassword(inputId, eyeId, eyeOffId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);
            if (input.type === 'password') {
                input.type = 'text'; eye.style.display = 'none'; eyeOff.style.display = 'block';
            } else {
                input.type = 'password'; eye.style.display = 'block'; eyeOff.style.display = 'none';
            }
        }

        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const fill = document.getElementById('strength-fill');
            const text = document.getElementById('strength-text');

            updateRequirement('req-length', password.length >= 8);
            updateRequirement('req-upper', /[A-Z]/.test(password));
            updateRequirement('req-lower', /[a-z]/.test(password));
            updateRequirement('req-number', /[0-9]/.test(password));
            updateRequirement('req-symbol', /[!@#$%^&*()_+\-={}\[\]:;"'|<>,.?/~`]/.test(password));

            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            const colors = ['#dc2626', '#ea580c', '#ca8a04', '#16a34a'];
            const labels = ['Muy débil', 'Débil', 'Moderada', 'Fuerte', 'Muy fuerte'];

            fill.style.width = `${(strength / 4) * 100}%`;
            fill.style.backgroundColor = colors[strength] || colors[3];
            text.textContent = labels[strength] || 'Muy fuerte';
            text.style.color = colors[strength] || colors[3];
            clearError('password');
        });

        function updateRequirement(id, isMet) {
            const el = document.getElementById(id);
            if (isMet) {
                el.classList.add('req-met'); el.querySelector('.req-icon').textContent = '\u2713';
            } else {
                el.classList.remove('req-met'); el.querySelector('.req-icon').textContent = '○';
            }
        }

        document.getElementById('telefono').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 4) { value = value.slice(0, 4) + '-' + value.slice(4, 8); }
            e.target.value = value;
        });
    </script>
</body>
</html>