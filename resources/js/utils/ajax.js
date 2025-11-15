/**
 * AJAX utility functions
 */
export class Ajax {
    /**
     * Get CSRF token from meta tag
     */
    static getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : null;
    }

    /**
     * Make GET request
     */
    static async get(url, options = {}) {
        return this.request('GET', url, null, options);
    }

    /**
     * Make POST request
     */
    static async post(url, data = null, options = {}) {
        return this.request('POST', url, data, options);
    }

    /**
     * Make PUT request
     */
    static async put(url, data = null, options = {}) {
        return this.request('PUT', url, data, options);
    }

    /**
     * Make DELETE request
     */
    static async delete(url, options = {}) {
        return this.request('DELETE', url, null, options);
    }

    /**
     * Make request
     */
    static async request(method, url, data = null, options = {}) {
        const headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.getCsrfToken(),
            'Accept': 'application/json',
            ...options.headers
        };

        const config = {
            method: method,
            headers: headers,
            ...options
        };

        if (data && method !== 'GET') {
            config.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, config);
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'Request failed');
            }
            
            return result;
        } catch (error) {
            console.error('AJAX request error:', error);
            throw error;
        }
    }
}




