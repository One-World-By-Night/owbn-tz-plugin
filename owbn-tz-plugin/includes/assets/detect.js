(function () {
    var cfg = window.owbnTzConfig;
    if (!cfg) return;

    function getCookie(name) {
        var m = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/[.$?*|{}()[\]\\\/+^]/g, '\\$&') + '=([^;]*)'));
        return m ? decodeURIComponent(m[1]) : null;
    }
    function setCookie(name, value, days) {
        var d = new Date();
        d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
        document.cookie = name + '=' + encodeURIComponent(value) +
            ';expires=' + d.toUTCString() +
            ';path=/;SameSite=Lax' + (location.protocol === 'https:' ? ';Secure' : '');
    }

    var detected = null;
    try { detected = Intl.DateTimeFormat().resolvedOptions().timeZone; } catch (e) {}

    // Auto-detect on load: set cookie + (if logged in and no user meta yet) save to profile.
    if (detected) {
        if (getCookie(cfg.cookieName) !== detected) {
            setCookie(cfg.cookieName, detected, cfg.cookieDays);
        }
        if (cfg.isLoggedIn && !cfg.hasUserMeta) {
            postTimezone(detected, true);
        }
    }

    // Wire up [owbn_tz_picker] forms.
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || !form.matches || !form.matches('form[data-owbn-tz-picker]')) return;
        e.preventDefault();
        var sel = form.querySelector('select[name="owbn_tz"]');
        var status = form.querySelector('.owbn-tz-picker-status');
        var value = sel ? sel.value : '';
        var apply = value || detected || '';
        if (!apply) return;
        setCookie(cfg.cookieName, apply, cfg.cookieDays);
        if (status) status.textContent = '…';
        if (cfg.isLoggedIn) {
            postTimezone(apply, false).then(function () {
                if (status) status.textContent = 'Saved.';
                setTimeout(function () { location.reload(); }, 400);
            }).catch(function () {
                if (status) status.textContent = 'Saved locally.';
                setTimeout(function () { location.reload(); }, 400);
            });
        } else {
            if (status) status.textContent = 'Saved.';
            setTimeout(function () { location.reload(); }, 400);
        }
    });

    function postTimezone(tz, isAuto) {
        return fetch(cfg.restUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': cfg.nonce
            },
            body: JSON.stringify({ timezone: tz, auto: !!isAuto })
        });
    }
})();
