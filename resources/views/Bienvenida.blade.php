<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Bienvenida</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS enlazado correctamente con Laravel -->
    <link rel="stylesheet" href="{{ asset('css/bienvenida.css') }}">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <p class="welcome-text">Bienvenido <span class="user-name"> {{ auth()->user()->name }} </span> a:</p>
            <div class="logo-section">
                <!-- Imagen corregida para Laravel -->
                <img src="{{ asset('img/logo.png') }}" alt="InmunoSV Logo" class="logo-img">
            </div>
            <h1 class="subtitle">Obtén tus recomendaciones personalizadas</h1>
            <p class="description">Completa la siguiente información para recibir recomendaciones de vacunación y salud adaptadas a tu perfil.</p>
        </div>

        <!-- Formulario -->
        <form
            class="form-content"
            id="bienvenidaForm"
            method="POST"
            action="{{ route('encuesta.store') }}">
            @csrf

            <!-- SECCIÓN 1: Información personal -->
            <div class="section">
                <div class="section-header">
                    <!-- Icono corregido para Laravel -->
                    <img src="{{ asset('img/icons/usuario.png') }}" alt="" class="section-icon" width="24" height="24">
                    <h2 class="section-title">1. Información personal</h2>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label class="input-label">Edad</label>

                        <div class="input-wrapper input-wrapper-inline">
                            <input
                                type="text"
                                class="form-input"
                                value="{{ \Carbon\Carbon::parse(auth()->user()->fecha_nacimiento)->age }}"
                                readonly>
                            <span class="suffix-outside">años</span>
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Sexo</label>

                        <div class="radio-group">

                            <label class="radio-option">
                                <input
                                    type="radio"
                                    class="radio-input"
                                    disabled
                                    {{ auth()->user()->genero == 'masculino' ? 'checked' : '' }}>
                                <span class="radio-custom"></span>

                                <img
                                    src="{{ asset('img/icons/simbolo-masculino.png') }}"
                                    alt=""
                                    class="radio-icon"
                                    width="18"
                                    height="18">

                                <span class="radio-label">Masculino</span>
                            </label>

                            <label class="radio-option">
                                <input
                                    type="radio"
                                    class="radio-input"
                                    disabled
                                    {{ auth()->user()->genero == 'femenino' ? 'checked' : '' }}>
                                <span class="radio-custom"></span>

                                <img
                                    src="{{ asset('img/icons/simbolo-femenino.png') }}"
                                    alt=""
                                    class="radio-icon"
                                    width="18"
                                    height="18">

                                <span class="radio-label">Femenino</span>
                            </label>

                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: Ubicación -->
            <div class="section">
                <div class="section-header">
                    <!-- Icono corregido para Laravel -->
                    <img src="{{ asset('img/icons/ubicacion.png') }}" alt="" class="section-icon" width="24" height="24">
                    <h2 class="section-title">2. Ubicación</h2>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label class="input-label" for="departamento">Departamento</label>
                        <div class="input-wrapper select-wrapper">
                            <input
                                type="text"
                                class="form-input"
                                value="{{ auth()->user()->departamento }}"
                                readonly>
                            <span class="select-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </span>
                        </div>
                        <span class="error-message" id="error-departamento"></span>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="municipio">Municipio</label>
                        <div class="input-wrapper select-wrapper">
                            <select id="municipio" name="municipio" class="form-select" required>
                                <option value="">Seleccione su municipio</option>

                                @foreach($municipios as $municipio)
                                <option value="{{ $municipio }}">
                                    {{ $municipio }}
                                </option>
                                @endforeach
                            </select>
                            <span class="select-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </span>
                        </div>
                        <span class="error-message" id="error-municipio"></span>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: Vacunas que ya se ha puesto -->
            <div class="section">
                <div class="section-header">
                    <!-- Icono corregido para Laravel -->
                    <img src="{{ asset('img/icons/vacuna.png') }}" alt="" class="section-icon" width="24" height="24">
                    <h2 class="section-title">3. Vacunas que ya se ha puesto</h2>
                </div>
                <p class="section-desc">Seleccione las vacunas que ya ha recibido:</p>

                <div class="checkbox-grid">
                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="1" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">1. Influenza (Gripe)</span>
                            <span class="checkbox-desc">Vacuna anual contra la influenza estacional</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="2" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">6. Hepatitis B</span>
                            <span class="checkbox-desc">Protege contra la hepatitis B</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="3" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">2. Neumocócica conjugada (PCV13)</span>
                            <span class="checkbox-desc">Protege contra neumonía y otras infecciones</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="4" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">7. COVID-19</span>
                            <span class="checkbox-desc">Vacuna contra el coronavirus</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="5" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">3. Neumocócica polisacárida (PPSV23)</span>
                            <span class="checkbox-desc">Protege contra neumonía y otras infecciones</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="6" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">8. Virus Sincitial Respiratorio (VSR)</span>
                            <span class="checkbox-desc">Protege contra infecciones respiratorias</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="7" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">4. Herpes Zóster (Culebrilla)</span>
                            <span class="checkbox-desc">Previene la culebrilla y neuralgia postherpética</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="8" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">9. Meningocócica</span>
                            <span class="checkbox-desc">Protege contra meningitis bacteriana</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="9" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">5. Tétanos, Difteria y Tosferina (Tdap)</span>
                            <span class="checkbox-desc">Refuerzo cada 10 años</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas[]" value="10" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">10. Hepatitis A</span>
                            <span class="checkbox-desc">Protege contra la hepatitis A</span>
                        </span>
                    </label>
                </div>
            </div>

            <!-- SECCIÓN 4: Condiciones médicas -->
            <div class="section">
                <div class="section-header">
                    <!-- Icono corregido para Laravel -->
                    <img src="{{ asset('img/icons/corazon.png') }}" alt="" class="section-icon" width="24" height="24">
                    <h2 class="section-title">4. Condiciones médicas</h2>
                </div>
                <p class="section-desc">¿Padece alguna condición médica?</p>

                <div class="condicion-grid">
                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="1" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Diabetes</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="2" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Hipertensión arterial</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="3" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Enfermedad cardíaca</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="4" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Enfermedad pulmonar crónica</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="5" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Insuficiencia renal</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="6" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Artritis / Artrosis</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="7" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Cáncer</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="8" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Enfermedad hepática</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="9" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Sistema inmunológico debilitado</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="10" class="condicion-input" id="condicion-ninguna">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Ninguna</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones[]" value="11" class="condicion-input" id="condicion-otra">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Otra condición</span>
                    </label>
                </div>

                <div class="otra-condicion" id="otraCondicionBox">
                    <input type="text" id="otra-condicion-text" name="otra_condicion" class="form-input" placeholder="Especifique" style="padding-left:16px;">
                </div>
            </div>

            <!-- Botón enviar -->
            <button type="submit" class="btn-submit" id="btnEnviar">
                Enviar
            </button>
        </form>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast"></div>

    <script>
        // Mostrar/Ocultar "Otra condición"
        document.getElementById('condicion-ninguna').addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('input[name="condiciones[]"]:not(#condicion-ninguna)').forEach(cb => {
                    cb.checked = false;
                });

                document.getElementById('otraCondicionBox').classList.remove('visible');
            }
        });

        document.querySelectorAll('input[name="condiciones[]"]:not(#condicion-ninguna)').forEach(cb => {
            cb.addEventListener('change', function() {

                if (this.checked) {
                    document.getElementById('condicion-ninguna').checked = false;
                }

                if (this.id === 'condicion-otra') {
                    document.getElementById('otraCondicionBox')
                        .classList.toggle('visible', this.checked);
                }
            });
        });

        // Validar municipio antes de enviar
        document.getElementById('bienvenidaForm').addEventListener('submit', function(e) {

            const municipio = document.getElementById('municipio').value;

            if (!municipio) {
                e.preventDefault();
                alert('Seleccione un municipio');
            }
        });
    </script>
</body>

</html>