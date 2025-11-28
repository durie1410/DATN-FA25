/**
 * Cache utility functions
 */
export class Cache {
    /**
     * Get item from localStorage
     */
    static get(key, defaultValue = null) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (e) {
            console.error('Cache get error:', e);
            return defaultValue;
        }
    }

    /**
     * Set item to localStorage
     */
    static set(key, value, ttl = null) {
        try {
            const item = {
                value: value,
                expires: ttl ? Date.now() + (ttl * 1000) : null
            };
            localStorage.setItem(key, JSON.stringify(item));
        } catch (e) {
            console.error('Cache set error:', e);
        }
    }

    /**
     * Remove item from localStorage
     */
    static remove(key) {
        try {
            localStorage.removeItem(key);
        } catch (e) {
            console.error('Cache remove error:', e);
        }
    }

    /**
     * Clear all cache
     */
    static clear() {
        try {
            localStorage.clear();
        } catch (e) {
            console.error('Cache clear error:', e);
        }
    }

    /**
     * Check if item exists and is not expired
     */
    static has(key) {
        try {
            const item = localStorage.getItem(key);
            if (!item) return false;

            const parsed = JSON.parse(item);
            if (parsed.expires && Date.now() > parsed.expires) {
                localStorage.removeItem(key);
                return false;
            }
            return true;
        } catch (e) {
            return false;
        }
    }
}




