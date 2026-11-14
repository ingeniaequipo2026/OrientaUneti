/**
 * OrientaUneti - Scripts de Cliente
 */

/* ============================================================
 *  WIDGET DE ASISTENTE — Ayuda Humana vía WhatsApp
 *  Número WhatsApp: cambiar WHATSAPP_NUMBER por el real
 *  Formato: código de país + número sin espacios ni guiones
 *  Ejemplo Venezuela: 584121234567
 * ============================================================ */
(function () {
    // ── CONFIGURACIÓN ─────────────────────────────────────────
    const WHATSAPP_NUMBER = '584111234567'; // ← Reemplazar por el número real
    const WHATSAPP_MENSAJE = encodeURIComponent(
        '¡Hola! Soy estudiante de la UNETI y necesito asistencia del equipo de Bienestar Estudiantil.'
    );
    const WHATSAPP_URL = `https://wa.me/${WHATSAPP_NUMBER}?text=${WHATSAPP_MENSAJE}`;

    // Solo mostrar en páginas de estudiantes
    const ES_PAGINA_ESTUDIANTE = window.location.pathname.startsWith('/estudiante')
        || window.location.pathname.startsWith('/actividades')
        || window.location.pathname.startsWith('/salud-mental')
        || window.location.pathname.startsWith('/emprendimientos')
        || window.location.pathname.startsWith('/contacto');

    if (!ES_PAGINA_ESTUDIANTE) return;

    // ── ESTILOS INLINE DEL WIDGET ─────────────────────────────
    const estilos = `
        #oru-widget-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: linear-gradient(135deg, #287A38 0%, #19319C 100%);
            color: #fff;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(25, 49, 156, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            z-index: 9998;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        #oru-widget-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 8px 24px rgba(25, 49, 156, 0.45);
        }
        #oru-widget-btn .oru-pulse {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 14px;
            height: 14px;
            background: #c0392b;
            border-radius: 50%;
            border: 2px solid #fff;
            animation: oruPulse 1.8s infinite;
        }
        @keyframes oruPulse {
            0%   { box-shadow: 0 0 0 0 rgba(192,57,43,0.6); }
            70%  { box-shadow: 0 0 0 8px rgba(192,57,43,0); }
            100% { box-shadow: 0 0 0 0 rgba(192,57,43,0); }
        }

        #oru-widget-panel {
            position: fixed;
            bottom: 92px;
            right: 24px;
            width: 320px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.14);
            z-index: 9999;
            display: none;
            flex-direction: column;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            animation: oruSlideUp 0.25s ease;
        }
        @keyframes oruSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        #oru-widget-panel.visible { display: flex; }

        .oru-panel-header {
            background: linear-gradient(135deg, #287A38 0%, #19319C 100%);
            color: #fff;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .oru-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .oru-panel-header .oru-info { flex: 1; }
        .oru-panel-header .oru-info strong { font-size: 0.95rem; display: block; }
        .oru-panel-header .oru-info small { font-size: 0.75rem; opacity: 0.8; }
        .oru-online-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4ade80;
            display: inline-block;
            margin-right: 4px;
        }
        .oru-close-btn {
            background: none;
            border: none;
            color: rgba(255,255,255,0.75);
            font-size: 1.1rem;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            transition: color 0.15s;
        }
        .oru-close-btn:hover { color: #fff; }

        .oru-panel-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-height: 100px;
        }

        .oru-bubble {
            background: #f1f5f9;
            border-radius: 12px 12px 12px 4px;
            padding: 10px 14px;
            font-size: 0.875rem;
            color: #2b303a;
            line-height: 1.5;
            max-width: 90%;
        }

        .oru-typing {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 10px 14px;
            background: #f1f5f9;
            border-radius: 12px 12px 12px 4px;
            width: fit-content;
        }
        .oru-typing span {
            width: 7px;
            height: 7px;
            background: #9ca3af;
            border-radius: 50%;
            animation: oruBounce 1.2s infinite;
        }
        .oru-typing span:nth-child(2) { animation-delay: 0.2s; }
        .oru-typing span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes oruBounce {
            0%, 60%, 100% { transform: translateY(0); }
            30%            { transform: translateY(-6px); }
        }

        .oru-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .oru-btn-si {
            background: linear-gradient(135deg, #287A38 0%, #19319C 100%);
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 7px 18px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.15s;
        }
        .oru-btn-si:hover { opacity: 0.9; }
        .oru-btn-no {
            background: transparent;
            color: #6b7280;
            border: 1.5px solid #d1d5db;
            border-radius: 20px;
            padding: 7px 18px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }
        .oru-btn-no:hover { border-color: #9ca3af; color: #374151; }

        .oru-wa-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #25D366;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
            width: 100%;
        }
        .oru-wa-btn:hover { background: #1ebe5c; color: #fff; }
        .oru-wa-btn svg { flex-shrink: 0; }

        .oru-panel-footer {
            border-top: 1px solid #f0f0f0;
            padding: 8px 18px;
            font-size: 0.7rem;
            color: #9ca3af;
            text-align: center;
        }
    `;

    const styleEl = document.createElement('style');
    styleEl.textContent = estilos;
    document.head.appendChild(styleEl);

    // ── HTML DEL WIDGET ───────────────────────────────────────
    const html = `
        <button id="oru-widget-btn" aria-label="Abrir asistente de ayuda">
            <span class="oru-pulse"></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 1a5 5 0 1 0 0 10A5 5 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
            </svg>
        </button>

        <div id="oru-widget-panel" role="dialog" aria-modal="true" aria-label="Asistente OrientaUneti">
            <div class="oru-panel-header">
                <div class="oru-avatar">🎓</div>
                <div class="oru-info">
                    <strong>Asistente OrientaUneti</strong>
                    <small><span class="oru-online-dot"></span>Bienestar Estudiantil</small>
                </div>
                <button class="oru-close-btn" id="oru-close" aria-label="Cerrar">✕</button>
            </div>
            <div class="oru-panel-body" id="oru-body">
                <!-- Contenido dinámico -->
            </div>
            <div class="oru-panel-footer">OrientaUneti · UNETI</div>
        </div>
    `;

    const wrapper = document.createElement('div');
    wrapper.innerHTML = html;
    document.body.appendChild(wrapper);

    // ── LÓGICA ────────────────────────────────────────────────
    const btn   = document.getElementById('oru-widget-btn');
    const panel = document.getElementById('oru-widget-panel');
    const body  = document.getElementById('oru-body');
    const close = document.getElementById('oru-close');

    // Mostrar/ocultar panel
    btn.addEventListener('click', () => {
        const abierto = panel.classList.toggle('visible');
        btn.setAttribute('aria-expanded', abierto);
        if (abierto && body.children.length === 0) iniciarConversacion();
    });

    close.addEventListener('click', () => {
        panel.classList.remove('visible');
        btn.setAttribute('aria-expanded', 'false');
    });

    // Iniciar la conversación con animación de escritura
    function iniciarConversacion() {
        mostrarTyping().then(() => {
            agregarBurbuja('¡Hola! 👋 Soy el asistente de <strong>Bienestar Estudiantil</strong>.');
            return delay(900);
        }).then(() => {
            mostrarTyping().then(() => {
                agregarBurbuja('¿Necesitas hablar con un <strong>orientador humano</strong> del equipo UNETI?');
                mostrarOpciones();
            });
        });
    }

    function mostrarOpciones() {
        const div = document.createElement('div');
        div.className = 'oru-actions';
        div.innerHTML = `
            <button class="oru-btn-si" id="oru-si">✅ Sí, necesito ayuda</button>
            <button class="oru-btn-no" id="oru-no">No, gracias</button>
        `;
        body.appendChild(div);
        scrollBottom();

        document.getElementById('oru-si').addEventListener('click', respuestaSi);
        document.getElementById('oru-no').addEventListener('click', respuestaNo);
    }

    function respuestaSi() {
        limpiarAcciones();
        agregarBurbujaUsuario('Sí, necesito ayuda');
        mostrarTyping().then(() => {
            agregarBurbuja('¡Perfecto! Te conectamos ahora con un orientador. 💚');
            return delay(700);
        }).then(() => {
            mostrarTyping().then(() => {
                agregarBurbuja('Presiona el botón para abrir <strong>WhatsApp</strong> y un miembro del equipo te atenderá a la brevedad.');
                const waDiv = document.createElement('div');
                waDiv.innerHTML = `
                    <a href="${WHATSAPP_URL}" target="_blank" rel="noopener noreferrer" class="oru-wa-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                        </svg>
                        Contactar por WhatsApp
                    </a>
                `;
                body.appendChild(waDiv);
                scrollBottom();
            });
        });
    }

    function respuestaNo() {
        limpiarAcciones();
        agregarBurbujaUsuario('No, gracias');
        mostrarTyping().then(() => {
            agregarBurbuja('¡Entendido! 😊 Si en algún momento necesitas ayuda, aquí estaré.');
            return delay(700);
        }).then(() => {
            const div = document.createElement('div');
            div.innerHTML = `<button class="oru-btn-no" id="oru-reiniciar" style="font-size:0.8rem;">🔄 Volver a preguntar</button>`;
            body.appendChild(div);
            scrollBottom();
            document.getElementById('oru-reiniciar').addEventListener('click', () => {
                body.innerHTML = '';
                iniciarConversacion();
            });
        });
    }

    // ── HELPERS ───────────────────────────────────────────────
    function agregarBurbuja(html) {
        const el = document.createElement('div');
        el.className = 'oru-bubble';
        el.innerHTML = html;
        body.appendChild(el);
        scrollBottom();
    }

    function agregarBurbujaUsuario(texto) {
        const el = document.createElement('div');
        el.style.cssText = 'align-self:flex-end;background:#e8ecf8;border-radius:12px 12px 4px 12px;padding:9px 14px;font-size:0.875rem;color:#2b303a;max-width:90%;';
        el.textContent = texto;
        body.appendChild(el);
        scrollBottom();
    }

    function mostrarTyping() {
        const el = document.createElement('div');
        el.className = 'oru-typing';
        el.innerHTML = '<span></span><span></span><span></span>';
        el.id = 'oru-typing-indicator';
        body.appendChild(el);
        scrollBottom();
        return delay(1100).then(() => {
            const t = document.getElementById('oru-typing-indicator');
            if (t) t.remove();
        });
    }

    function limpiarAcciones() {
        const acciones = body.querySelector('.oru-actions');
        if (acciones) acciones.remove();
    }

    function scrollBottom() {
        body.scrollTop = body.scrollHeight;
    }

    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
})();

/* ============================================================
 *  SCRIPTS GENERALES DE LA APLICACIÓN
 * ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar tooltips de Bootstrap si existen
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirmación para eliminar elementos
    const deleteForms = document.querySelectorAll('.form-eliminar');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const confirmed = confirm('¿Está seguro de que desea eliminar este registro? Esta acción no se puede deshacer.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // Confirmación para cancelar inscripción
    const cancelEnrollForms = document.querySelectorAll('.form-cancelar-inscripcion');
    cancelEnrollForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const confirmed = confirm('¿Está seguro de cancelar su inscripción? Su cupo quedará libre para otro estudiante.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // Auto-cierre de alertas flash después de 6 segundos
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = bootstrap.Alert.getInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        });
    }, 6000);
});
