export default function flmOptin(el) {
    return {
        endpoint: el.dataset.endpoint || '',
        nonce: el.dataset.nonce || '',
        source: el.dataset.source || 'acf-block',
        email: '',
        first_name: '',
        website: '',
        loading: false,
        ok: false,
        error: null,
        retryAfter: null,

        async submit() {
            this.error = null;
            this.ok = false;
            this.retryAfter = null;
            this.loading = true;

            try {
                const res = await fetch(this.endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-FLM-Nonce': this.nonce
                    },
                    body: JSON.stringify({
                        email: this.email,
                        first_name: this.first_name,
                        website: this.website,
                        source: this.source,
                        _flm_nonce: this.nonce
                    })
                });

                if (res.ok) {
                    this.ok = true;
                    this.email = '';
                    this.first_name = '';
                    return;
                }

                const retry = res.headers.get('Retry-After');
                if (retry) this.retryAfter = parseInt(retry, 10);

                let payload = {};
                try { payload = await res.json(); } catch (_) {}

                const code = payload?.code || payload?.error || 'unknown_error';

                if (res.status === 403 && code === 'bad_nonce') {
                    this.error = 'Sesión caducada. Recarga la página e inténtalo de nuevo.';
                } else if (res.status === 422 && (code === 'invalid_email_format' || code === 'invalid_email')) {
                    this.error = 'El email no es válido.';
                } else if (res.status === 422 && code === 'honeypot_triggered') {
                    this.error = 'Error de validación.';
                } else if (res.status === 422 && code === 'disposable_email_blocked') {
                    this.error = 'No aceptamos correos desechables.';
                } else if (res.status === 429 && code === 'rate_limited_ip') {
                    this.error = this.retryAfter ? `Demasiadas peticiones. Prueba en ${this.retryAfter}s.` : 'Demasiadas peticiones. Inténtalo más tarde.';
                } else if (res.status === 429 && code === 'rate_limited_email') {
                    this.error = this.retryAfter ? `Has superado el límite para este email. Prueba en ${(this.retryAfter/60)|0} min.` : 'Has superado el límite para este email.';
                } else {
                    this.error = 'No se pudo enviar. Inténtalo más tarde.';
                }
            } catch (e) {
                this.error = 'Conexión fallida. Revisa tu red.';
            } finally {
                this.loading = false;
            }
        }
    };
}
