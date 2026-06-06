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
            <p class="welcome-text">Bienvenido <span class="user-name" id="userName">Jose</span> a:</p>
            <div class="logo-section">
                <!-- Imagen corregida para Laravel -->
                <img src="{{ asset('img/logo.png') }}" alt="InmunoSV Logo" class="logo-img">
            </div>
            <h1 class="subtitle">Obtén tus recomendaciones personalizadas</h1>
            <p class="description">Completa la siguiente información para recibir recomendaciones de vacunación y salud adaptadas a tu perfil.</p>
        </div>

        <!-- Formulario -->
        <form class="form-content" id="bienvenidaForm" novalidate>

            <!-- SECCIÓN 1: Información personal -->
            <div class="section">
                <div class="section-header">
                    <!-- Icono corregido para Laravel -->
                    <img src="{{ asset('img/icons/usuario.png') }}" alt="" class="section-icon" width="24" height="24">
                    <h2 class="section-title">1. Información personal</h2>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label class="input-label" for="edad">Edad</label>
                        <div class="input-wrapper input-wrapper-inline">
                            <input type="number" id="edad" name="edad" class="form-input" placeholder="Ej. 72" min="18" max="120" required>
                            <span class="suffix-outside">años</span>
                        </div>
                        <span class="error-message" id="error-edad"></span>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Sexo</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="sexo" value="masculino" class="radio-input" id="sexo-masculino">
                                <span class="radio-custom"></span>
                                <!-- Icono corregido para Laravel -->
                                <img src="{{ asset('img/icons/simbolo-masculino.png') }}" alt="" class="radio-icon" width="18" height="18">
                                <span class="radio-label">Masculino</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="sexo" value="femenino" class="radio-input" id="sexo-femenino">
                                <span class="radio-custom"></span>
                                <!-- Icono corregido para Laravel -->
                                <img src="{{ asset('img/icons/simbolo-femenino.png') }}" alt="" class="radio-icon" width="18" height="18">
                                <span class="radio-label">Femenino</span>
                            </label>
                        </div>
                        <span class="error-message" id="error-sexo"></span>
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
                            <select id="departamento" name="departamento" class="form-select" required>
                                <option value="" disabled selected>Seleccione su departamento</option>
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
                            <span class="select-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </span>
                        </div>
                        <span class="error-message" id="error-departamento"></span>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="municipio">Municipio</label>
                        <div class="input-wrapper select-wrapper">
                            <select id="municipio" name="municipio" class="form-select" required>
                                <option value="" disabled selected>Seleccione su municipio</option>
                            </select>
                            <span class="select-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6"/>
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
                        <input type="checkbox" name="vacunas" value="influenza" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">1. Influenza (Gripe)</span>
                            <span class="checkbox-desc">Vacuna anual contra la influenza estacional</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas" value="hepatitis-b" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">6. Hepatitis B</span>
                            <span class="checkbox-desc">Protege contra la hepatitis B</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas" value="neumococo-pcv13" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">2. Neumocócica conjugada (PCV13)</span>
                            <span class="checkbox-desc">Protege contra neumonía y otras infecciones</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas" value="covid19" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">7. COVID-19</span>
                            <span class="checkbox-desc">Vacuna contra el coronavirus</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas" value="neumococo-ppv23" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">3. Neumocócica polisacárida (PPSV23)</span>
                            <span class="checkbox-desc">Protege contra neumonía y otras infecciones</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas" value="vrs" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">8. Virus Sincitial Respiratorio (VSR)</span>
                            <span class="checkbox-desc">Protege contra infecciones respiratorias</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas" value="herpes-zoster" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">4. Herpes Zóster (Culebrilla)</span>
                            <span class="checkbox-desc">Previene la culebrilla y neuralgia postherpética</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas" value="meningococo" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">9. Meningocócica</span>
                            <span class="checkbox-desc">Protege contra meningitis bacteriana</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas" value="tdap" class="checkbox-input">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            <span class="checkbox-title">5. Tétanos, Difteria y Tosferina (Tdap)</span>
                            <span class="checkbox-desc">Refuerzo cada 10 años</span>
                        </span>
                    </label>

                    <label class="checkbox-option">
                        <input type="checkbox" name="vacunas" value="hepatitis-a" class="checkbox-input">
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
                        <input type="checkbox" name="condiciones" value="diabetes" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Diabetes</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="hipertension" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Hipertensión arterial</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="cardiaca" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Enfermedad cardíaca</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="pulmonar" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Enfermedad pulmonar crónica</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="renal" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Insuficiencia renal</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="artritis" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Artritis / Artrosis</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="cancer" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Cáncer</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="hepatica" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Enfermedad hepática</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="inmunologico" class="condicion-input">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Sistema inmunológico debilitado</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="ninguna" class="condicion-input" id="condicion-ninguna">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Ninguna</span>
                    </label>

                    <label class="condicion-option">
                        <input type="checkbox" name="condiciones" value="otra" class="condicion-input" id="condicion-otra">
                        <span class="condicion-custom"></span>
                        <span class="condicion-label">Otra condición</span>
                    </label>
                </div>

                <div class="otra-condicion" id="otraCondicionBox">
                    <input type="text" id="otra-condicion-text" class="form-input" placeholder="Especifique" style="padding-left:16px;">
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
        // Data de municipios igual...
        const municipiosPorDepto = {
            'ahuachapan': ['Ahuachapán', 'Apaneca', 'Atiquizaya', 'Concepción de Ataco', 'El Refugio', 'Guaymango', 'Jujutla', 'San Francisco Menéndez', 'San Lorenzo', 'San Pedro Puxtla', 'Tacuba', 'Turín'],
            'santa-ana': ['Santa Ana', 'Candelaria de la Frontera', 'Chalchuapa', 'Coatepeque', 'El Congo', 'El Porvenir', 'Masahuat', 'Metapán', 'San Antonio Pajonal', 'San Sebastián Salitrillo', 'Santa Rosa Guachipilín', 'Santiago de la Frontera', 'Texistepeque'],
            'sonsonate': ['Sonsonate', 'Acajutla', 'Armenia', 'Caluco', 'Cuisnahuat', 'Izalco', 'Juayúa', 'Nahuizalco', 'Nahulingo', 'Salcoatitán', 'San Antonio del Monte', 'San Julián', 'Santa Catarina Masahuat', 'Santa Isabel Ishuatán', 'Santo Domingo de Guzmán', 'Sonzacate'],
            'chalatenango': ['Chalatenango', 'Agua Caliente', 'Arcatao', 'Azacualpa', 'Cancasque', 'Citalá', 'Comalapa', 'Concepción Quezaltepeque', 'Dulce Nombre de María', 'El Carrizal', 'El Paraíso', 'La Laguna', 'La Palma', 'La Reina', 'Las Vueltas', 'Nueva Concepción', 'Nueva Trinidad', 'Nombre de Jesús', 'Ojos de Agua', 'Potonico', 'San Antonio de la Cruz', 'San Antonio Los Ranchos', 'San Fernando', 'San Francisco Lempa', 'San Francisco Morazán', 'San Ignacio', 'San Isidro Labrador', 'San Luis del Carmen', 'San Miguel de Mercedes', 'San Rafael', 'Santa Rita', 'Tejutla'],
            'la-libertad': ['Santa Tecla', 'Antiguo Cuscatlán', 'Chiltiupán', 'Ciudad Arce', 'Colón', 'Comasagua', 'Huizúcar', 'Jayaque', 'Jicalapa', 'La Libertad', 'Nuevo Cuscatlán', 'Opico', 'Quezaltepeque', 'Sacacoyo', 'San Juan Opico', 'San Matías', 'San Pablo Tacachico', 'Talnique', 'Tamanique', 'Teotepeque', 'Tepecoyo', 'Zaragoza'],
            'san-salvador': ['San Salvador', 'Aguilares', 'Apopa', 'Ayutuxtepeque', 'Cuscatancingo', 'Delgado', 'El Paisnal', 'Guazapa', 'Ilopango', 'Mejicanos', 'Nejapa', 'Panchimalco', 'Rosario de Mora', 'San Marcos', 'San Martín', 'Santiago Texacuangos', 'Santo Tomás', 'Soyapango', 'Tonacatepeque'],
            'cuscatlan': ['Cojutepeque', 'Candelaria', 'El Carmen', 'El Rosario', 'Monte San Juan', 'Oratorio de Concepción', 'San Bartolomé Perulapía', 'San Cristóbal', 'San José Guayabal', 'San Pedro Perulapán', 'San Rafael Cedros', 'San Ramón', 'Santa Cruz Analquito', 'Santa Cruz Michapa', 'Suchitoto', 'Tenancingo'],
            'la-paz': ['Zacatecoluca', 'Cuyultitán', 'El Rosario', 'Jerusalén', 'Mercedes La Ceiba', 'Olocuilta', 'Paraíso de Osorio', 'San Antonio Masahuat', 'San Emigdio', 'San Francisco Chinameca', 'San Juan Nonualco', 'San Juan Talpa', 'San Juan Tepezontes', 'San Luis La Herradura', 'San Luis Talpa', 'San Miguel Tepezontes', 'San Pedro Masahuat', 'San Pedro Nonualco', 'San Rafael Obrajuelo', 'Santa María Ostuma', 'Santiago Nonualco', 'Tapalhuaca'],
            'cabanas': ['Sensuntepeque', 'Cinquera', 'Dolores', 'Guacotecti', 'Ilobasco', 'Jutiapa', 'San Isidro', 'Tejutepeque', 'Victoria'],
            'san-vicente': ['San Vicente', 'Apastepeque', 'Guadalupe', 'San Cayetano Istepeque', 'San Esteban Catarina', 'San Ildefonso', 'San Lorenzo', 'San Sebastián', 'Santa Clara', 'Santo Domingo', 'Tecoluca', 'Tepetitán', 'Verapaz'],
            'usulutan': ['Usulután', 'Alegría', 'Berlín', 'California', 'Concepción Batres', 'El Triunfo', 'Ereguayquín', 'Estanzuelas', 'Jiquilisco', 'Jucuapa', 'Jucuarán', 'Mercedes Umaña', 'Nueva Granada', 'Ozatlán', 'Puerto El Triunfo', 'San Agustín', 'San Buenaventura', 'San Dionisio', 'San Francisco Javier', 'Santa Elena', 'Santa María', 'Santiago de María', 'Tecapán'],
            'san-miguel': ['San Miguel', 'Carolina', 'Chapeltique', 'Chinameca', 'Chirilagua', 'Ciudad Barrios', 'Comacarán', 'El Tránsito', 'Lolotique', 'Moncagua', 'Nueva Guadalupe', 'Nuevo Edén de San Juan', 'Quelepa', 'San Antonio del Mosco', 'San Gerardo', 'San Jorge', 'San Luis de la Reina', 'San Rafael Oriente', 'Sesori', 'Uluazapa'],
            'morazan': ['San Francisco Gotera', 'Arambala', 'Cacaopera', 'Chilanga', 'Corinto', 'Delicias de Concepción', 'El Divisadero', 'El Rosario', 'Gualococti', 'Guatajiagua', 'Joateca', 'Jocoaitique', 'Jocoro', 'Lolotiquillo', 'Meanguera', 'Osicala', 'Perquín', 'San Carlos', 'San Fernando', 'San Isidro', 'San Simón', 'Sensembra', 'Sociedad', 'Torola', 'Yamabal', 'Yoloaiquín'],
            'la-union': ['La Unión', 'Anamorós', 'Bolívar', 'Concepción de Oriente', 'Conchagua', 'El Carmen', 'El Sauce', 'Intipucá', 'Lislique', 'Meanguera del Golfo', 'Nueva Esparta', 'Pasaquina', 'Polorós', 'San Alejo', 'San José', 'Santa Rosa de Lima', 'Yayantique', 'Yucuaiquín']
        };

        function cargarNombreUsuario() {
            const loggedEmail = sessionStorage.getItem('inmunosv_logged_user');
            if (loggedEmail) {
                const users = JSON.parse(localStorage.getItem('inmunosv_users') || '[]');
                const user = users.find(u => u.email === loggedEmail);
                if (user && user.nombre) {
                    document.getElementById('userName').textContent = user.nombre;
                }
            }
        }

        document.getElementById('departamento').addEventListener('change', function() {
            const depto = this.value;
            const municipioSelect = document.getElementById('municipio');
            municipioSelect.innerHTML = '<option value="" disabled selected>Seleccione su municipio</option>';

            if (municipiosPorDepto[depto]) {
                municipiosPorDepto[depto].forEach(mun => {
                    const option = document.createElement('option');
                    option.value = mun.toLowerCase().replace(/\s+/g, '-');
                    option.textContent = mun;
                    municipioSelect.appendChild(option);
                });
            }
            clearError('departamento');
            clearError('municipio');
        });

        document.getElementById('condicion-ninguna').addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('input[name="condiciones"]:not(#condicion-ninguna)').forEach(cb => {
                    cb.checked = false;
                });
                document.getElementById('otraCondicionBox').classList.remove('visible');
            }
        });

        document.querySelectorAll('input[name="condiciones"]:not(#condicion-ninguna)').forEach(cb => {
            cb.addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('condicion-ninguna').checked = false;
                }
                if (this.id === 'condicion-otra') {
                    document.getElementById('otraCondicionBox').classList.toggle('visible', this.checked);
                }
            });
        });

        function showError(fieldId, message) {
            const errorEl = document.getElementById('error-' + fieldId);
            const inputEl = document.getElementById(fieldId);
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.classList.add('visible');
            }
            if (inputEl) {
                inputEl.classList.add('input-error');
            }
        }

        function clearError(fieldId) {
            const errorEl = document.getElementById('error-' + fieldId);
            const inputEl = document.getElementById(fieldId);
            if (errorEl) {
                errorEl.textContent = '';
                errorEl.classList.remove('visible');
            }
            if (inputEl) {
                inputEl.classList.remove('input-error');
            }
        }

        function clearAllErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.textContent = '';
                el.classList.remove('visible');
            });
            document.querySelectorAll('.form-input, .form-select').forEach(el => {
                el.classList.remove('input-error');
            });
        }

        function showToast(type, message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast ' + type;
            void toast.offsetWidth;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 4000);
        }

        function validarFormulario() {
            let isValid = true;
            clearAllErrors();

            const edad = document.getElementById('edad').value.trim();
            if (!edad) {
                showError('edad', 'La edad es obligatoria');
                isValid = false;
            } else if (isNaN(edad) || edad < 18 || edad > 120) {
                showError('edad', 'Ingrese una edad válida (18-120 años)');
                isValid = false;
            }

            const sexo = document.querySelector('input[name="sexo"]:checked');
            if (!sexo) {
                const errorSexo = document.getElementById('error-sexo');
                if (errorSexo) {
                    errorSexo.textContent = 'Seleccione su sexo';
                    errorSexo.classList.add('visible');
                }
                isValid = false;
            }

            const departamento = document.getElementById('departamento').value;
            if (!departamento) {
                showError('departamento', 'Seleccione un departamento');
                isValid = false;
            }

            const municipio = document.getElementById('municipio').value;
            if (!municipio) {
                showError('municipio', 'Seleccione un municipio');
                isValid = false;
            }

            return isValid;
        }

        // ============================================
        // ENVÍO DEL FORMULARIO CON REDIRECCIÓN LARAVEL
        // ============================================
        document.getElementById('bienvenidaForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!validarFormulario()) {
                showToast('error', 'Por favor corrija los errores del formulario');
                return;
            }

            const loggedEmail = sessionStorage.getItem('inmunosv_logged_user');
            if (!loggedEmail) {
                showToast('error', 'Debe iniciar sesión para continuar');
                return;
            }

            const users = JSON.parse(localStorage.getItem('inmunosv_users') || '[]');
            const userIndex = users.findIndex(u => u.email === loggedEmail);

            if (userIndex === -1) {
                showToast('error', 'Usuario no encontrado');
                return;
            }

            const vacunasSeleccionadas = Array.from(document.querySelectorAll('input[name="vacunas"]:checked')).map(cb => cb.value);
            const condicionesSeleccionadas = Array.from(document.querySelectorAll('input[name="condiciones"]:checked')).map(cb => cb.value);
            const otraCondicion = document.getElementById('otra-condicion-text').value.trim();

            users[userIndex].perfilSalud = {
                edad: parseInt(document.getElementById('edad').value),
                sexo: document.querySelector('input[name="sexo"]:checked').value,
                departamento: document.getElementById('departamento').value,
                municipio: document.getElementById('municipio').value,
                vacunasAplicadas: vacunasSeleccionadas,
                condicionesMedicas: condicionesSeleccionadas,
                otraCondicion: otraCondicion || null,
                fechaRegistro: new Date().toISOString(),
                formularioCompletado: true
            };

            localStorage.setItem('inmunosv_users', JSON.stringify(users));

            // REDIRECCIÓN INTERNA CORREGIDA CON URL DE LARAVEL
            window.location.href = "{{ url('/notificaciones') }}";
        });

        document.getElementById('edad').addEventListener('input', function() {
            clearError('edad');
        });

        document.querySelectorAll('input[name="sexo"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const errorSexo = document.getElementById('error-sexo');
                if (errorSexo) {
                    errorSexo.textContent = '';
                    errorSexo.classList.remove('visible');
                }
            });
        });

        document.getElementById('municipio').addEventListener('change', function() {
            clearError('municipio');
        });

        document.addEventListener('DOMContentLoaded', function() {
            cargarNombreUsuario();
        });
    </script>
</body>
</html>