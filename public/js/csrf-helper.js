// CSRF Token Helper for AJAX requests
(function() {
    'use strict';

    // Get CSRF token from meta tag
    function getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : null;
    }

    // Setup CSRF token for all AJAX requests
    if (typeof $ !== 'undefined') {
        // jQuery
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });
    }

    // Setup CSRF token for fetch API
    if (typeof fetch !== 'undefined') {
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            options.headers = {
                ...options.headers,
                'X-CSRF-TOKEN': getCsrfToken()
            };
            return originalFetch(url, options);
        };
    }

    // Setup CSRF token for XMLHttpRequest
    const originalXHROpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function(method, url, async, user, password) {
        this.addEventListener('readystatechange', function() {
            if (this.readyState === 1) {
                this.setRequestHeader('X-CSRF-TOKEN', getCsrfToken());
            }
        });
        return originalXHROpen.call(this, method, url, async, user, password);
    };

    // Helper function to refresh CSRF token
    window.refreshCsrfToken = function() {
        return fetch('/csrf-refresh', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update meta tag
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                token.setAttribute('content', data.csrf_token);
            }
            return data.csrf_token;
        })
        .catch(error => {
            console.error('Error refreshing CSRF token:', error);
            return null;
        });
    };

    // Auto-refresh CSRF token every 30 minutes
    setInterval(function() {
        refreshCsrfToken();
    }, 30 * 60 * 1000); // 30 minutes

})();























