/**
 * chatbot.js — Muni Asistente Virtual
 * InmunoSV · Laravel 12
 * ─────────────────────────────────────────────────────────────────────────────
 * Toda la lógica de interfaz del chatbot vive aquí.
 * El Blade solo referencia este archivo.
 */

// ─── Estado global ────────────────────────────────────────────────────────────

const Muni = {
    conversacionId:  window.MUNI_CONFIG?.conversacion_id  ?? null,
    csrfToken:       window.MUNI_CONFIG?.csrf_token        ?? '',
    urls: {
        mensaje:      window.MUNI_CONFIG?.urls?.mensaje      ?? '/muni/mensaje',
        audio:        window.MUNI_CONFIG?.urls?.audio        ?? '/muni/audio',
        historial:    window.MUNI_CONFIG?.urls?.historial    ?? '/muni/historial',
        conversacion: window.MUNI_CONFIG?.urls?.conversacion ?? '/muni/conversacion',
        nueva:        window.MUNI_CONFIG?.urls?.nueva        ?? '/muni/nueva',
    },
    grabando:        false,
    mediaRecorder:   null,
    audioChunks:     [],
    streamAudio:     null,
};

// ─── Inicialización ───────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    inicializarChat();
    inicializarReproductoresExistentes(); // reproductores renderizados por Blade al recargar
    scrollToBottom();
    enfocarInput();
});

function inicializarChat() {
    const input = document.getElementById('chatInput');
    if (input) {
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                enviarMensaje();
            }
        });
    }
}

// ─── Enviar mensaje de texto ──────────────────────────────────────────────────

async function enviarMensaje() {
    const input   = document.getElementById('chatInput');
    const mensaje = input?.value?.trim();

    if (!mensaje) return;

    // Mostrar mensaje del usuario inmediatamente
    agregarMensaje('usuario', mensaje, 'texto');
    input.value = '';

    // Mostrar indicador de escritura
    const typingId = mostrarTyping();

    try {
        const res = await fetch(Muni.urls.mensaje, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': Muni.csrfToken,
                'Accept':       'application/json',
            },
            body: JSON.stringify({
                mensaje:         mensaje,
                conversacion_id: Muni.conversacionId,
                tipo:            'texto',
            }),
        });

        const data = await res.json();

        ocultarTyping(typingId);

        if (data.success) {
            Muni.conversacionId = data.conversacion_id;
            agregarMensaje('bot', data.respuesta, 'texto', data.timestamp);
        } else {
            agregarMensaje('bot', 'Ocurrió un error. Por favor intenta de nuevo.', 'texto');
        }

    } catch (err) {
        ocultarTyping(typingId);
        agregarMensaje('bot', 'No pude conectarme. Verifica tu conexión e intenta nuevamente.', 'texto');
        console.error('Error al enviar mensaje:', err);
    }

    scrollToBottom();
}

// ─── Grabación y envío de audio ───────────────────────────────────────────────

async function toggleAudio() {
    if (Muni.grabando) {
        detenerGrabacion();
    } else {
        await iniciarGrabacion();
    }
}

async function iniciarGrabacion() {
    try {
        Muni.streamAudio = await navigator.mediaDevices.getUserMedia({ audio: true });
        Muni.audioChunks = [];

        Muni.mediaRecorder = new MediaRecorder(Muni.streamAudio, {
            mimeType: 'audio/webm;codecs=opus',
        });

        Muni.mediaRecorder.ondataavailable = (e) => {
            if (e.data.size > 0) Muni.audioChunks.push(e.data);
        };

        Muni.mediaRecorder.onstop = async () => {
            const blob = new Blob(Muni.audioChunks, { type: 'audio/webm' });
            await enviarAudio(blob);
        };

        Muni.mediaRecorder.start();
        Muni.grabando = true;
        actualizarBotonAudio(true);

    } catch (err) {
        console.error('Error al acceder al micrófono:', err);
        mostrarToast('No se pudo acceder al micrófono. Verifica los permisos.', 'error');
    }
}

function detenerGrabacion() {
    if (Muni.mediaRecorder && Muni.grabando) {
        Muni.mediaRecorder.stop();
        Muni.streamAudio?.getTracks().forEach(t => t.stop());
        Muni.grabando = false;
        actualizarBotonAudio(false);
    }
}

async function enviarAudio(blob) {
    // 1. Reproductor provisional con blob URL mientras sube
    const blobUrl   = URL.createObjectURL(blob);
    const previewId = agregarMensajeAudio(blobUrl);
    const typingId  = mostrarTyping();

    const formData = new FormData();
    formData.append('audio', blob, 'grabacion.webm');
    if (Muni.conversacionId) {
        formData.append('conversacion_id', Muni.conversacionId);
    }

    try {
        const res  = await fetch(Muni.urls.audio, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': Muni.csrfToken },
            body:    formData,
        });
        const data = await res.json();
        ocultarTyping(typingId);

        if (data.success) {
            Muni.conversacionId = data.conversacion_id;

            // 2. Sustituir blob URL por URL permanente del servidor
            //    así el audio sigue reproducible al recargar la página
            if (data.audio_url) {
                actualizarUrlReproductor(previewId, data.audio_url);
            }

            // 3. Transcripción debajo del reproductor
            if (data.transcripcion) {
                agregarTranscripcion(previewId, data.transcripcion);
            }

            // 4. Respuesta del bot
            agregarMensaje('bot', data.respuesta, 'texto', data.timestamp);
        } else {
            agregarMensaje('bot', data.message ?? 'No pude procesar el audio.', 'texto');
        }

    } catch (err) {
        ocultarTyping(typingId);
        agregarMensaje('bot', 'Error al enviar el audio. Intenta nuevamente.', 'texto');
        console.error('Error al enviar audio:', err);
    }

    scrollToBottom();
}

// ─── Construcción de mensajes en el DOM ───────────────────────────────────────

function agregarMensaje(emisor, texto, tipo = 'texto', timestamp = null) {
    const contenedor = document.getElementById('chatMessages');
    const hora       = timestamp ?? horaActual();
    const esBot      = emisor === 'bot';

    const wrapper = document.createElement('div');
    wrapper.classList.add('message', esBot ? 'message-bot' : 'message-user');

    const textoHTML = formatearTextoBot(texto);

    if (esBot) {
        wrapper.innerHTML = `
            <div class="message-avatar">
                ${iconoMuni()}
                <span class="message-status-dot"></span>
            </div>
            <div class="message-content">
                <div class="message-bubble">${textoHTML}</div>
                <span class="message-time">${hora}</span>
            </div>`;
    } else {
        wrapper.innerHTML = `
            <div class="message-content">
                <div class="message-bubble">${escapeHtml(texto)}</div>
                <div class="message-meta">
                    <span class="message-time">${hora}</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
            </div>`;
    }

    contenedor.appendChild(wrapper);
    scrollToBottom();
}

// Crea el reproductor de audio en el chat.
// Retorna el ID único del elemento para poder actualizarlo después.
function agregarMensajeAudio(audioUrl) {
    const contenedor = document.getElementById('chatMessages');
    const hora       = horaActual();
    const id         = 'audio-msg-' + Date.now();

    const wrapper = document.createElement('div');
    wrapper.classList.add('message', 'message-user');
    wrapper.id = id;

    wrapper.innerHTML = `
        <div class="message-content">
            <div class="message-bubble audio-bubble">
                <div class="audio-player" data-url="${audioUrl}">
                    <button class="audio-play-btn" aria-label="Reproducir audio">
                        ${iconoPlay()}
                    </button>
                    <div class="audio-waveform">${waveformSVG()}</div>
                    <span class="audio-duration">0:00</span>
                </div>
            </div>
            <div class="message-meta">
                <span class="message-time">${hora}</span>
            </div>
        </div>`;

    contenedor.appendChild(wrapper);

    // Vincular el reproductor real al botón y cargar duración
    const player = wrapper.querySelector('.audio-player');
    const btn    = wrapper.querySelector('.audio-play-btn');
    vincularReproductor(player, btn, audioUrl);

    return id;
}

// Agrega la transcripción debajo del reproductor identificado por msgId
function agregarTranscripcion(msgId, texto) {
    const wrapper = document.getElementById(msgId);
    if (!wrapper) return;

    const audioBubble = wrapper.querySelector('.audio-bubble');
    if (!audioBubble) return;

    const transcDiv = document.createElement('div');
    transcDiv.classList.add('transcription');
    transcDiv.innerHTML = `
        <div class="transcription-label">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
                <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                <line x1="12" y1="19" x2="12" y2="23"/>
                <line x1="8"  y1="23" x2="16" y2="23"/>
            </svg>
            <span>Audio transcrito</span>
        </div>
        <div class="transcription-text">${escapeHtml(texto)}</div>`;

    // Insertar después de la burbuja de audio, antes del meta
    const meta = wrapper.querySelector('.message-meta');
    wrapper.querySelector('.message-content').insertBefore(transcDiv, meta);
}

// ─── Indicador de escritura ───────────────────────────────────────────────────

function mostrarTyping() {
    const contenedor = document.getElementById('chatMessages');
    const id         = 'typing-' + Date.now();

    const typing = document.createElement('div');
    typing.classList.add('message', 'message-bot', 'typing-indicator-wrapper');
    typing.id = id;
    typing.innerHTML = `
        <div class="message-avatar">${iconoMuni()}</div>
        <div class="message-content">
            <div class="message-bubble typing-bubble">
                <span class="typing-dot"></span>
                <span class="typing-dot"></span>
                <span class="typing-dot"></span>
            </div>
        </div>`;

    contenedor.appendChild(typing);
    scrollToBottom();
    return id;
}

function ocultarTyping(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

// ─── Historial de conversaciones ──────────────────────────────────────────────

async function cargarHistorial() {
    try {
        const res  = await fetch(Muni.urls.historial, {
            headers: { 'Accept': 'application/json' },
        });
        const data = await res.json();
        renderizarHistorial(data.conversaciones ?? []);
    } catch (err) {
        console.error('Error cargando historial:', err);
    }
}

function renderizarHistorial(conversaciones) {
    const lista = document.getElementById('historialLista');
    if (!lista) return;

    lista.innerHTML = '';

    if (conversaciones.length === 0) {
        lista.innerHTML = '<p class="historial-vacio">No tienes conversaciones anteriores.</p>';
        return;
    }

    conversaciones.forEach(conv => {
        const item = document.createElement('button');
        item.classList.add('historial-item');
        item.dataset.id = conv.id;
        item.innerHTML = `
            <span class="historial-titulo">${escapeHtml(conv.titulo)}</span>
            <span class="historial-preview">${escapeHtml(conv.ultimo_mensaje)}</span>
            <span class="historial-fecha">${escapeHtml(conv.fecha)}</span>`;

        item.addEventListener('click', () => cargarConversacion(conv.id));
        lista.appendChild(item);
    });
}

async function cargarConversacion(id) {
    try {
        const res  = await fetch(Muni.urls.conversacion, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': Muni.csrfToken,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ conversacion_id: id }),
        });
        const data = await res.json();

        Muni.conversacionId = data.conversacion_id;

        // Limpiar y repintar mensajes
        const contenedor = document.getElementById('chatMessages');
        contenedor.innerHTML = '';

        data.mensajes.forEach(m => {
            agregarMensaje(m.emisor, m.mensaje, m.tipo, m.timestamp);
        });

        scrollToBottom();

    } catch (err) {
        console.error('Error cargando conversación:', err);
    }
}

async function nuevaConversacion() {
    try {
        const res  = await fetch(Muni.urls.nueva, {
            method:  'POST',
            headers: {
                'X-CSRF-TOKEN': Muni.csrfToken,
                'Accept':       'application/json',
            },
        });
        const data = await res.json();

        if (data.success) {
            Muni.conversacionId = data.conversacion_id;
            const contenedor    = document.getElementById('chatMessages');
            contenedor.innerHTML = '';
            agregarMensaje('bot',
                '¡Hola! Soy Muni, tu asistente de salud. ¿En qué puedo ayudarte hoy?',
                'texto'
            );
        }
    } catch (err) {
        console.error('Error al crear conversación:', err);
    }
}

// ─── Sidebar (móvil) ──────────────────────────────────────────────────────────

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (sidebar && overlay) {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
    }
}

// ─── Reproductor de audio ─────────────────────────────────────────────────────

/**
 * Vincula un objeto Audio a un reproductor del DOM.
 * Maneja duración real, animación del waveform y estado play/pause.
 */
function vincularReproductor(player, btn, url) {
    const audio    = new Audio(url);
    const durSpan  = player.querySelector('.audio-duration');
    const waveform = player.querySelector('.audio-waveform');

    // Guardar referencia para actualizarUrlReproductor()
    player._audio = audio;

    // Mostrar duración real cuando los metadatos estén listos
    audio.addEventListener('loadedmetadata', () => {
        if (isFinite(audio.duration)) {
            durSpan.textContent = formatearDuracion(audio.duration);
        }
    });

    // Actualizar contador durante la reproducción
    audio.addEventListener('timeupdate', () => {
        if (isFinite(audio.duration)) {
            const restante = audio.duration - audio.currentTime;
            durSpan.textContent = formatearDuracion(restante);
        }
    });

    // Al terminar: restaurar estado
    audio.addEventListener('ended', () => {
        btn.innerHTML = iconoPlay();
        waveform.classList.remove('playing');
        if (isFinite(audio.duration)) {
            durSpan.textContent = formatearDuracion(audio.duration);
        }
    });

    // Click en el botón
    btn.addEventListener('click', () => {
        // Pausar cualquier otro audio activo en el chat
        document.querySelectorAll('.audio-player').forEach(p => {
            if (p !== player && p._audio && !p._audio.paused) {
                p._audio.pause();
                p.querySelector('.audio-play-btn').innerHTML = iconoPlay();
                p.querySelector('.audio-waveform').classList.remove('playing');
            }
        });

        if (audio.paused) {
            audio.play();
            btn.innerHTML = iconoPause();
            waveform.classList.add('playing');
        } else {
            audio.pause();
            btn.innerHTML = iconoPlay();
            waveform.classList.remove('playing');
        }
    });
}

/**
 * Sustituye el blob URL por la URL permanente del servidor
 * una vez que la subida al servidor terminó exitosamente.
 */
function actualizarUrlReproductor(msgId, nuevaUrl) {
    const wrapper = document.getElementById(msgId);
    if (!wrapper) return;

    const player = wrapper.querySelector('.audio-player');
    if (!player) return;

    // Guardar posición actual y estado
    const audioViejo  = player._audio;
    const estabaSonando = audioViejo && !audioViejo.paused;
    audioViejo?.pause();

    // Crear nuevo Audio con URL permanente
    const audioNuevo = new Audio(nuevaUrl);
    player.dataset.url = nuevaUrl;

    // Re-vincular eventos con la nueva URL
    const btn = wrapper.querySelector('.audio-play-btn');
    vincularReproductor(player, btn, nuevaUrl);

    // Actualizar botón si estaba reproduciendo
    if (!estabaSonando) {
        btn.innerHTML = iconoPlay();
        wrapper.querySelector('.audio-waveform').classList.remove('playing');
    }
}

/**
 * Inicializa los reproductores que vienen renderizados desde el Blade (al recargar).
 * Lee data-url del elemento .audio-player y vincula los eventos.
 */
function inicializarReproductoresExistentes() {
    document.querySelectorAll('.audio-player[data-url]').forEach(player => {
        const url = player.dataset.url;
        if (!url || url === '') return;
        const btn = player.querySelector('.audio-play-btn');
        if (btn) vincularReproductor(player, btn, url);
    });
}

function formatearDuracion(segundos) {
    const s = Math.floor(segundos);
    const m = Math.floor(s / 60);
    const r = s % 60;
    return `${m}:${r.toString().padStart(2, '0')}`;
}

// ─── Utilidades ───────────────────────────────────────────────────────────────

function scrollToBottom() {
    const contenedor = document.getElementById('chatMessages');
    if (contenedor) {
        contenedor.scrollTo({ top: contenedor.scrollHeight, behavior: 'smooth' });
    }
}

function enfocarInput() {
    const input = document.getElementById('chatInput');
    if (input) input.focus();
}

function horaActual() {
    return new Date().toLocaleTimeString('es-SV', {
        hour:   '2-digit',
        minute: '2-digit',
        hour12: true,
    });
}

/**
 * Formatea el texto del bot:
 * convierte saltos de línea, negritas (**), viñetas (•) en HTML.
 */
function formatearTextoBot(texto) {
    let html = escapeHtml(texto);

    // Negritas: **texto**
    html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');

    // Viñetas: líneas que empiezan con •
    const lineas = html.split('\n');
    let resultado = '';
    let enLista   = false;

    for (const linea of lineas) {
        const trimmed = linea.trim();
        if (trimmed.startsWith('•')) {
            if (!enLista) {
                resultado += '<ul class="vacuna-lista">';
                enLista = true;
            }
            resultado += `<li>${trimmed.substring(1).trim()}</li>`;
        } else {
            if (enLista) {
                resultado += '</ul>';
                enLista = false;
            }
            resultado += trimmed ? `<p>${trimmed}</p>` : '';
        }
    }

    if (enLista) resultado += '</ul>';

    return resultado;
}

function escapeHtml(str) {
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return String(str).replace(/[&<>"']/g, m => map[m]);
}

function mostrarToast(mensaje, tipo = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${tipo}`;
    toast.textContent = mensaje;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

function actualizarBotonAudio(grabando) {
    const btn = document.querySelector('.btn-audio');
    if (!btn) return;
    btn.classList.toggle('recording', grabando);
    btn.setAttribute('aria-label', grabando ? 'Detener grabación' : 'Enviar audio');
    btn.title = grabando ? 'Haz clic para detener' : 'Haz clic para grabar';
}

// ─── Íconos SVG inline ────────────────────────────────────────────────────────

function iconoMuni() {
    return `<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="4" y="4" width="16" height="16" rx="4" fill="#3933B2"/>
        <circle cx="9" cy="10" r="1.5" fill="white"/>
        <circle cx="15" cy="10" r="1.5" fill="white"/>
        <path d="M9 15c1 1.5 2.5 2 3 2s2-0.5 3-2" stroke="white" stroke-width="1.5" stroke-linecap="round" fill="none"/>
    </svg>`;
}

function iconoPlay() {
    return `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="5 3 19 12 5 21 5 3"/>
    </svg>`;
}

function iconoPause() {
    return `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>
    </svg>`;
}

function waveformSVG() {
    return `<svg width="120" height="24" viewBox="0 0 120 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
    </svg>`;
}