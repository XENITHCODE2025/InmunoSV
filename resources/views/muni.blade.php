<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InmunoSV — Muni</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/muni.css') }}">
</head>
<body>

    {{-- ═══════════════════════════════════════════════════════════════ SIDEBAR --}}
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
                    <line x1="10" y1="9"  x2="8" y2="9"/>
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
                </svg>
                <span>Muni</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="#" class="nav-item logout"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Cerrar sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </aside>

    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    {{-- ════════════════════════════════════════════════════════ CONTENIDO MAIN --}}
    <main class="main-content">

        {{-- Header móvil --}}
        <header class="mobile-header">
            <button class="menu-btn" onclick="toggleSidebar()" aria-label="Abrir menú">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6"  x2="21" y2="6"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <div class="mobile-chat-header">
                <div class="mobile-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
                        <circle cx="9"  cy="10" r="1.5" fill="white"/>
                        <circle cx="15" cy="10" r="1.5" fill="white"/>
                        <path d="M9 15c1 1.5 2.5 2 3 2s2-0.5 3-2" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
                <div class="mobile-chat-info">
                    <span class="mobile-chat-name">Muni</span>
                    <span class="mobile-chat-status">Asistente de salud</span>
                </div>
            </div>
            <div class="user-avatar" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
        </header>

        {{-- Header desktop --}}
        <header class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
                        <circle cx="9"  cy="10" r="1.5" fill="white"/>
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
            <button class="btn-menu-chat" aria-label="Nueva conversación" onclick="nuevaConversacion()" title="Nueva conversación">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5"  y1="12" x2="19" y2="12"/>
                </svg>
            </button>
        </header>

        {{-- ══════════════════════════════════════════════ ÁREA DE MENSAJES --}}
        <div class="chat-messages" id="chatMessages">

            <div class="date-separator"><span>Hoy</span></div>

            {{-- Mensaje de bienvenida estático --}}
            <div class="message message-bot">
                <div class="message-avatar">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
                        <circle cx="9"  cy="10" r="1.5" fill="white"/>
                        <circle cx="15" cy="10" r="1.5" fill="white"/>
                        <path d="M9 15c1 1.5 2.5 2 3 2s2-0.5 3-2" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    </svg>
                    <span class="message-status-dot"></span>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <p>¡Hola, <strong>{{ auth()->user()->nombre ?? 'usuario' }}</strong>! 👋 Soy <strong>Muni</strong>, tu asistente de salud.</p>
                        <p>Estoy aquí para ayudarte con información sobre vacunas, recordatorios y cualquier duda de salud preventiva.</p>
                        <p>¿En qué puedo ayudarte hoy?</p>
                    </div>
                    <span class="message-time">{{ now()->format('h:i a') }}</span>
                </div>
            </div>

            {{-- Mensajes previos de la sesión (si los hay) --}}
            @foreach ($mensajes as $msg)
                @if ($msg->emisor === 'usuario')
                    <div class="message message-user">
                        <div class="message-content">

                            @if ($msg->tipo === 'audio')
                                {{-- ── Burbuja con reproductor de audio ── --}}
                                <div class="message-bubble audio-bubble">
                                    <div class="audio-player" data-url="{{ $msg->audio_url ?? '' }}">
                                        <button class="audio-play-btn"
                                                aria-label="Reproducir audio">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="5 3 19 12 5 21 5 3"/>
                                            </svg>
                                        </button>
                                        <div class="audio-waveform">
                                            <svg width="120" height="24" viewBox="0 0 120 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0"   y="10" width="2" height="4"  rx="1" fill="#3933B2"/>
                                                <rect x="4"   y="8"  width="2" height="8"  rx="1" fill="#3933B2"/>
                                                <rect x="8"   y="6"  width="2" height="12" rx="1" fill="#3933B2"/>
                                                <rect x="12"  y="4"  width="2" height="16" rx="1" fill="#3933B2"/>
                                                <rect x="16"  y="8"  width="2" height="8"  rx="1" fill="#3933B2"/>
                                                <rect x="20"  y="6"  width="2" height="12" rx="1" fill="#3933B2"/>
                                                <rect x="24"  y="10" width="2" height="4"  rx="1" fill="#3933B2"/>
                                                <rect x="28"  y="8"  width="2" height="8"  rx="1" fill="#3933B2"/>
                                                <rect x="32"  y="6"  width="2" height="12" rx="1" fill="#3933B2"/>
                                                <rect x="36"  y="4"  width="2" height="16" rx="1" fill="#3933B2"/>
                                                <rect x="40"  y="8"  width="2" height="8"  rx="1" fill="#3933B2"/>
                                                <rect x="44"  y="10" width="2" height="4"  rx="1" fill="#3933B2"/>
                                                <rect x="48"  y="6"  width="2" height="12" rx="1" fill="#3933B2"/>
                                                <rect x="52"  y="8"  width="2" height="8"  rx="1" fill="#3933B2"/>
                                                <rect x="56"  y="4"  width="2" height="16" rx="1" fill="#3933B2"/>
                                                <rect x="60"  y="10" width="2" height="4"  rx="1" fill="#3933B2"/>
                                                <rect x="64"  y="6"  width="2" height="12" rx="1" fill="#3933B2"/>
                                                <rect x="68"  y="8"  width="2" height="8"  rx="1" fill="#3933B2"/>
                                                <rect x="72"  y="4"  width="2" height="16" rx="1" fill="#3933B2"/>
                                                <rect x="76"  y="10" width="2" height="4"  rx="1" fill="#3933B2"/>
                                                <rect x="80"  y="6"  width="2" height="12" rx="1" fill="#3933B2"/>
                                                <rect x="84"  y="8"  width="2" height="8"  rx="1" fill="#3933B2"/>
                                                <rect x="88"  y="10" width="2" height="4"  rx="1" fill="#3933B2"/>
                                                <rect x="92"  y="4"  width="2" height="16" rx="1" fill="#3933B2"/>
                                                <rect x="96"  y="8"  width="2" height="8"  rx="1" fill="#3933B2"/>
                                                <rect x="100" y="6"  width="2" height="12" rx="1" fill="#3933B2"/>
                                                <rect x="104" y="10" width="2" height="4"  rx="1" fill="#3933B2"/>
                                                <rect x="108" y="8"  width="2" height="8"  rx="1" fill="#3933B2"/>
                                                <rect x="112" y="6"  width="2" height="12" rx="1" fill="#3933B2"/>
                                                <rect x="116" y="10" width="2" height="4"  rx="1" fill="#3933B2"/>
                                            </svg>
                                        </div>
                                        <span class="audio-duration">0:07</span>
                                    </div>
                                </div>
                                {{-- Transcripción debajo del reproductor --}}
                                <div class="transcription">
                                    <div class="transcription-label">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
                                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                                            <line x1="12" y1="19" x2="12" y2="23"/>
                                            <line x1="8"  y1="23" x2="16" y2="23"/>
                                        </svg>
                                        <span>Audio transcrito</span>
                                    </div>
                                    <div class="transcription-text">{{ $msg->mensaje }}</div>
                                </div>

                            @else
                                {{-- ── Burbuja de texto normal ── --}}
                                <div class="message-bubble">
                                    <p>{{ $msg->mensaje }}</p>
                                </div>
                            @endif

                            <div class="message-meta">
                                <span class="message-time">{{ $msg->created_at->format('h:i a') }}</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>

                        </div>
                    </div>
                @else
                    <div class="message message-bot">
                        <div class="message-avatar">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
                                <circle cx="9"  cy="10" r="1.5" fill="white"/>
                                <circle cx="15" cy="10" r="1.5" fill="white"/>
                                <path d="M9 15c1 1.5 2.5 2 3 2s2-0.5 3-2" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                            </svg>
                            <span class="message-status-dot"></span>
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                {!! nl2br(e($msg->mensaje)) !!}
                            </div>
                            <span class="message-time">{{ $msg->created_at->format('h:i a') }}</span>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>{{-- /chat-messages --}}

        {{-- ══════════════════════════════════════════════ INPUT --}}
        <div class="chat-input-area">
            <div class="input-wrapper">
                <input
                    type="text"
                    class="chat-input"
                    id="chatInput"
                    placeholder="Escribe tu mensaje..."
                    autocomplete="off"
                    maxlength="1000"
                >
                <div class="input-actions">
                    <button class="btn-audio" aria-label="Grabar audio" onclick="toggleAudio()" title="Grabar audio">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                            <line x1="12" y1="19" x2="12" y2="23"/>
                            <line x1="8"  y1="23" x2="16" y2="23"/>
                        </svg>
                    </button>
                    <button class="btn-send" aria-label="Enviar mensaje" onclick="enviarMensaje()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="input-hint">Puedes enviar mensajes de texto o grabar un audio. Muni transcribirá el audio para ayudarte mejor.</p>
        </div>

    </main>

    {{-- ═══════════════════════════════════════ CONFIGURACIÓN PARA chatbot.js --}}
    {{--
        Pasamos datos de PHP a JS a través de un objeto global MUNI_CONFIG.
        chatbot.js lo lee al inicializarse.
        ¡Nunca expongas datos sensibles aquí!
    --}}
    <script>
        window.MUNI_CONFIG = {
            conversacion_id: {{ $conversacion->id ?? 'null' }},
            csrf_token:      '{{ csrf_token() }}',
            urls: {
                mensaje:      '{{ route("muni.mensaje") }}',
                audio:        '{{ route("muni.audio") }}',
                historial:    '{{ route("muni.historial") }}',
                conversacion: '{{ route("muni.conversacion") }}',
                nueva:        '{{ route("muni.nueva") }}',
            },
        };
    </script>

    {{-- JS del chatbot — toda la lógica está en este archivo --}}
    <script src="{{ asset('js/chatbot.js') }}"></script>

</body>
</html>