/**
 * Notification utility functions
 */
export class Notifications {
    /**
     * Show success notification
     */
    static success(message, duration = 3000) {
        this.show(message, 'success', duration);
    }

    /**
     * Show error notification
     */
    static error(message, duration = 5000) {
        this.show(message, 'error', duration);
    }

    /**
     * Show info notification
     */
    static info(message, duration = 3000) {
        this.show(message, 'info', duration);
    }

    /**
     * Show warning notification
     */
    static warning(message, duration = 4000) {
        this.show(message, 'warning', duration);
    }

    /**
     * Show notification
     */
    static show(message, type = 'info', duration = 3000) {
        // Check if toastr or similar library is available
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
            return;
        }

        // Fallback: Create simple notification
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, duration);
    }
}




