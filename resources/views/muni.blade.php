<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmunoSV — Muni</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/muni.css') }}">
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
            <a href="{{ url('/muni') }}" class="nav-item active" aria-current="page">
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
            <div class="mobile-chat-header">
                <div class="mobile-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
                        <circle cx="9" cy="10" r="1.5" fill="white"/>
                        <circle cx="15" cy="10" r="1.5" fill="white"/>
                        <path d="M9 15c1 1.5 2.5 2 3 2s2-0.5 3-2" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
                <div class="mobile-chat-info">
                    <span class="mobile-chat-name">Muni</span>
                    <span class="mobile-chat-status">Asistente de salud</span>
                </div>
            </div>
            <div class="user-avatar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
        </header>

        <header class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
                        <circle cx="9" cy="10" r="1.5" fill="white"/>
                        <circle cx="15" cy="10" r="1.5" fill="white"/>
                        <path d="M9 15c1 1.5 2.5 2 3 2s2-0.5 3-2" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    </svg>
                    <span class="chat-status-dot"></span>
                </div>
                <div class="chat-title-info">
                    <h1 class="chat-title">Muni</h1>
                    <p class="chat-subtitle">Asistente de salud</p>
                </div>
            </div>
            <button class="btn-menu-chat" aria-label="Más opciones">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="5" r="1"/>
                    <circle cx="12" cy="12" r="1"/>
                    <circle cx="12" cy="19" r="1"/>
                </svg>
            </button>
        </header>

        <div class="chat-messages" id="chatMessages">
            <div class="date-separator">
                <span>Hoy</span>
            </div>

            <div class="message message-bot">
                <div class="message-avatar">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
                        <circle cx="9" cy="10" r="1.5" fill="white"/>
                        <circle cx="15" cy="10" r="1.5" fill="white"/>
                        <path d="M9 15c1 1.5 2.5 2 3 2s2-0.5 3-2" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    </svg>
                    <span class="message-status-dot"></span>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <p>¡Hola! 👋 Soy Muni, tu asistente de salud.</p>
                        <p>Estoy aquí para ayudarte con información sobre vacunas, recordatorios y cualquier duda que tengas.</p>
                        <p>¿En qué puedo ayudarte hoy?</p>
                    </div>
                    <span class="message-time">10:28 a. m.</span>
                </div>
            </div>

            <div class="message message-user">
                <div class="message-content">
                    <div class="message-bubble audio-bubble">
                        <div class="audio-player">
                            <button class="audio-play-btn" aria-label="Reproducir audio">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="5 3 19 12 5 21 5 3"/>
                                </svg>
                            </button>
                            <div class="audio-waveform">
                                <svg width="120" height="24" viewBox="0 0 120 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0" y="10" width="2" height="4" rx="1" fill="#3933B2"/>
                                    <rect x="4" y="8" width="2" height="8" rx="1" fill="#3933B2"/>
                                    <rect x="8" y="6" width="2" height="12" rx="1" fill="#3933B2"/>
                                    <rect x="12" y="4" width="2" height="16" rx="1" fill="#3933B2"/>
                                    <rect x="16" y="8" width="2" height="8" rx="1" fill="#3933B2"/>
                                    <rect x="20" y="6" width="2" height="12" rx="1" fill="#3933B2"/>
                                    <rect x="24" y="10" width="2" height="4" rx="1" fill="#3933B2"/>
                                    <rect x="28" y="8" width="2" height="8" rx="1" fill="#3933B2"/>
                                    <rect x="32" y="6" width="2" height="12" rx="1" fill="#3933B2"/>
                                    <rect x="36" y="4" width="2" height="16" rx="1" fill="#3933B2"/>
                                    <rect x="40" y="8" width="2" height="8" rx="1" fill="#3933B2"/>
                                    <rect x="44" y="10" width="2" height="4" rx="1" fill="#3933B2"/>
                                    <rect x="48" y="6" width="2" height="12" rx="1" fill="#3933B2"/>
                                    <rect x="52" y="8" width="2" height="8" rx="1" fill="#3933B2"/>
                                    <rect x="56" y="4" width="2" height="16" rx="1" fill="#3933B2"/>
                                    <rect x="60" y="10" width="2" height="4" rx="1" fill="#3933B2"/>
                                    <rect x="64" y="6" width="2" height="12" rx="1" fill="#3933B2"/>
                                    <rect x="68" y="8" width="2" height="8" rx="1" fill="#3933B2"/>
                                    <rect x="72" y="4" width="2" height="16" rx="1" fill="#3933B2"/>
                                    <rect x="76" y="10" width="2" height="4" rx="1" fill="#3933B2"/>
                                    <rect x="80" y="6" width="2" height="12" rx="1" fill="#3933B2"/>
                                    <rect x="84" y="8" width="2" height="8" rx="1" fill="#3933B2"/>
                                    <rect x="88" y="10" width="2" height="4" rx="1" fill="#3933B2"/>
                                    <rect x="92" y="4" width="2" height="16" rx="1" fill="#3933B2"/>
                                    <rect x="96" y="8" width="2" height="8" rx="1" fill="#3933B2"/>
                                    <rect x="100" y="6" width="2" height="12" rx="1" fill="#3933B2"/>
                                    <rect x="104" y="10" width="2" height="4" rx="1" fill="#3933B2"/>
                                    <rect x="108" y="8" width="2" height="8" rx="1" fill="#3933B2"/>
                                    <rect x="112" y="6" width="2" height="12" rx="1" fill="#3933B2"/>
                                    <rect x="116" y="10" width="2" height="4" rx="1" fill="#3933B2"/>
                                </svg>
                            </div>
                            <span class="audio-duration">0:07</span>
                        </div>
                    </div>
                    <div class="transcription">
                        <div class="transcription-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                                <line x1="12" y1="19" x2="12" y2="23"/>
                                <line x1="8" y1="23" x2="16" y2="23"/>
                            </svg>
                            <span>Audio transcrito</span>
                        </div>
                        <div class="transcription-text">Hola Muni, ¿qué vacunas me faltan según mi edad?</div>
                    </div>
                    <div class="message-meta">
                        <span class="message-time">10:29 a. m.</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="message message-bot">
                <div class="message-avatar">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
                        <circle cx="9" cy="10" r="1.5" fill="white"/>
                        <circle cx="15" cy="10" r="1.5" fill="white"/>
                        <path d="M9 15c1 1.5 2.5 2 3 2s2-0.5 3-2" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    </svg>
                    <span class="message-status-dot"></span>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <p>Según tu edad ({{ auth()->user()->fecha_nacimiento ? \Carbon\Carbon::parse(auth()->user()->fecha_nacimiento)->age : 27 }} años), estas son las vacunas que podrías necesitar o tener pendientes:</p>
                        <ul class="vacuna-lista">
                            <li>Influenza (anual)</li>
                            <li>Virus del Papiloma Humano (VPH)</li>
                            <li>Hepatitis B (si no completaste el esquema)</li>
                            <li>Tétanos (refuerzo cada 10 años)</li>
                        </ul>
                        <p>Te recomiendo consultar con tu médico para más detalles.</p>
                        <p>¿Hay algo más en lo que pueda ayudarte?</p>
                    </div>
                    <span class="message-time">10:30 a. m.</span>
                </div>
            </div>
        </div>

        <div class="chat-input-area">
            <div class="input-wrapper">
                <input 
                    type="text" 
                    class="chat-input" 
                    id="chatInput" 
                    placeholder="Escribe tu mensaje..."
                    onkeypress="if(event.key==='Enter') sendMessage()"
                >
                <div class="input-actions">
                    <button class="btn-audio" aria-label="Enviar audio" onclick="toggleAudio()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                            <line x1="12" y1="19" x2="12" y2="23"/>
                            <line x1="8" y1="23" x2="16" y2="23"/>
                        </svg>
                    </button>
                    <button class="btn-send" aria-label="Enviar mensaje" onclick="sendMessage()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="input-hint">Puedes enviar mensajes de texto o audio. Muni transcribirá el audio para ayudarte mejor.</p>
        </div>
    </main>

    <script>
        // Extraemos la edad dinámica calculada en el backend de Laravel
        const userAge = "{{ auth()->user()->fecha_nacimiento ? \Carbon\Carbon::parse(auth()->user()->fecha_nacimiento)->age : 27 }}";

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        function sendMessage() {
            const input = document.getElementById('chatInput');
            const text = input.value.trim();
            if (!text) return;

            const container = document.getElementById('chatMessages');
            const time = new Date().toLocaleTimeString('es-SV', { hour: '2-digit', minute: '2-digit' }) + ' a. m.';

            const userMessage = document.createElement('div');
            userMessage.className = 'message message-user';
            userMessage.innerHTML = `
                <div class="message-content">
                    <div class="message-bubble">
                        <p>${text}</p>
                    </div>
                    <div class="message-meta">
                        <span class="message-time">${time}</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                </div>
            `;
            container.appendChild(userMessage);
            input.value = '';
            container.scrollTop = container.scrollHeight;

            setTimeout(() => {
                const botMessage = document.createElement('div');
                botMessage.className = 'message message-bot';
                botMessage.innerHTML = `
                    <div class="message-avatar">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
                            <circle cx="9" cy="10" r="1.5" fill="white"/>
                            <circle cx="15" cy="10" r="1.5" fill="white"/>
                            <path d="M9 15c1 1.5 2.5 2 3 2s2-0.5 3-2" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                        </svg>
                        <span class="message-status-dot"></span>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p>${getMuniResponse(text)}</p>
                        </div>
                        <span class="message-time">${new Date().toLocaleTimeString('es-SV', { hour: '2-digit', minute: '2-digit' })} a. m.</span>
                    </div>
                `;
                container.appendChild(botMessage);
                container.scrollTop = container.scrollHeight;
            }, 1000 + Math.random() * 1000);
        }

        function getMuniResponse(userText) {
            const text = userText.toLowerCase();
            const responses = {
                'hola': '¡Hola! 👋 Soy Muni, tu asistente de salud. ¿En qué puedo ayudarte hoy?',
                'vacunas': `Según tu edad registrada (${userAge} años), podrías necesitar revisar tus esquemas de Influenza anual, VPH, Hepatitis B o Tétanos según corresponda.`,
                'edad': `Al tener ${userAge} años, adaptamos tus sugerencias médicas de forma preventiva. ¿Deseas saber más de alguna vacuna en específico?`,
                'influenza': 'La vacuna contra la Influenza se recomienda aplicarla anualmente, especialmente durante la temporada de mayor circulación del virus.',
                'vph': 'El Virus del Papiloma Humano (VPH) se gestiona de forma preventiva según el grupo de edad para evitar complicaciones crónicas.',
                'hepatitis': 'La Hepatitis B requiere un esquema completo de dosis. Puedes validar el estado actual dentro de tu panel "Mi Control".',
                'tetanos': 'El refuerzo de Tétanos se aconseja renovarlo de forma regular cada 10 años.',
                'recordatorio': 'Puedes gestionar tus recordatorios de vacunación en la sección "Recordatorios" del menú lateral.',
                'recordatorios': 'En la sección Recordatorios puedes ver tus próximas vacunas, citas pendientes y configurar alertas.',
                'perfil': 'Puedes ver y actualizar tu información personal en la sección "Mi Perfil".',
                'control': 'En "Mi Control" puedes consultar tu historial de vacunas aplicadas y las que tienes pendientes.',
                'gracias': '¡Con gusto! 😊 Estoy aquí para ayudarte cuando lo necesites.',
                'adios': '¡Hasta luego! Cuídate y no olvides mantener tu calendario de vacunación al día.'
            }; 

            for (let key in responses) {
                if (text.includes(key)) return responses[key];
            }

            return 'Comprendo... ¿podrías darme más detalles? Puedo proporcionarte orientación sobre vacunas, alertas programadas o la navegación dentro de tu historial.';
        }

        function toggleAudio() {
            alert('Función de audio en desarrollo. Por ahora puedes escribir tu mensaje.');
        }
    </script>
</body>
</html>