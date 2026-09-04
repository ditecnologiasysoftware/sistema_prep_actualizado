(function (window) {
    'use strict';

    function normalizeIcon(icon) {
        var value = String(icon || 'info').toLowerCase();
        var aliases = { danger: 'error', fail: 'error', failed: 'error', ok: 'success' };
        value = aliases[value] || value;
        return ['success', 'error', 'warning', 'info', 'question'].indexOf(value) >= 0 ? value : 'info';
    }

    function parseResponse(response) {
        if (response && typeof response === 'object') return response;
        var value = String(response || '').replace(/^\uFEFF/, '').trim();
        if (!value) throw new Error('Respuesta vacía');
        return JSON.parse(value);
    }

    function fire(options) {
        if (!window.Swal || typeof window.Swal.fire !== 'function') {
            window.alert((options.title ? options.title + '\n' : '') + (options.text || ''));
            return Promise.resolve({ isConfirmed: true });
        }
        return window.Swal.fire(options);
    }

    function notify(message, title, icon) {
        return fire({
            title: title || (normalizeIcon(icon) === 'success' ? 'Listo' : 'Aviso'),
            text: message || 'La operación terminó correctamente.',
            icon: normalizeIcon(icon),
            confirmButtonText: 'Aceptar'
        });
    }

    function runCallbacks(response) {
        var callbacks = response.funcion || response.funciones || [];
        var params = response.params || [];
        if (typeof callbacks === 'string') callbacks = [callbacks];
        if (!Array.isArray(callbacks)) return;

        callbacks.forEach(function (name, index) {
            var fn = window[name];
            var args = Array.isArray(params[index]) ? params[index] : [];
            if (typeof fn === 'function') fn.apply(window, args);
            else if (window.console && console.warn) console.warn('Función de retorno no encontrada:', name);
        });
    }

    window.PrepAlert = {
        confirm: function (options) {
            var defaults = {
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            };
            Object.keys(options || {}).forEach(function (key) { defaults[key] = options[key]; });
            return fire(defaults);
        },
        notify: notify,
        fromResponse: function (response) {
            return notify(response.mensaje || response.message,
                response.titulo || response.titulo_mensaje || response.title,
                response.tipo || response.icon);
        },
        error: function (message) { return notify(message, 'Error', 'error'); },
        parseResponse: parseResponse,
        runCallbacks: runCallbacks,
        ajaxError: function (xhr, status) {
            var messages = {
                parsererror: 'El servidor devolvió una respuesta inválida.',
                timeout: 'La solicitud tardó demasiado tiempo.',
                abort: 'La solicitud fue cancelada.'
            };
            var message = messages[status];
            if (!message && xhr.status === 0) message = 'No fue posible conectar con el servidor. Verifica tu conexión.';
            if (!message && xhr.status === 404) message = 'No se encontró la ruta solicitada (404).';
            if (!message && xhr.status >= 500) message = 'Ocurrió un error interno en el servidor (' + xhr.status + ').';
            return notify(message || 'No fue posible completar la operación.', 'Error', 'error');
        }
    };
})(window);
