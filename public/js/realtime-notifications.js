/**
 * Real-time Notification Client
 * Handles WebSocket connections and real-time notifications
 */
class RealTimeNotificationClient {
    constructor() {
        this.socket = null;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectInterval = 5000;
        this.userId = null;
        this.notificationContainer = null;
        this.unreadBadge = null;
        
        this.init();
    }

    init() {
        // Get user ID from meta tag or global variable
        this.userId = document.querySelector('meta[name="user-id"]')?.content || window.userId;
        
        if (!this.userId) {
            console.warn('User ID not found, real-time notifications disabled');
            return;
        }

        // Initialize UI elements
        this.initUI();
        
        // Connect to WebSocket
        this.connect();
        
        // Set up periodic heartbeat
        this.setupHeartbeat();
        
        // Mark user as online
        this.markUserOnline();
    }

    initUI() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('notificationContainer')) {
            const container = document.createElement('div');
            container.id = 'notificationContainer';
            container.className = 'position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }
        
        this.notificationContainer = document.getElementById('notificationContainer');
        
        // Create unread badge if it doesn't exist
        if (!document.getElementById('unreadBadge')) {
            const badge = document.createElement('span');
            badge.id = 'unreadBadge';
            badge.className = 'badge bg-danger position-absolute top-0 start-100 translate-middle';
            badge.style.display = 'none';
            
            // Find notification icon and add badge
            const notificationIcon = document.querySelector('[data-notification-icon]');
            if (notificationIcon) {
                notificationIcon.style.position = 'relative';
                notificationIcon.appendChild(badge);
            }
        }
        
        this.unreadBadge = document.getElementById('unreadBadge');
    }

    connect() {
        try {
            // For demo purposes, we'll simulate WebSocket with polling
            // In production, replace with actual WebSocket connection
            this.simulateConnection();
        } catch (error) {
            console.error('Failed to connect to WebSocket:', error);
            this.scheduleReconnect();
        }
    }

    simulateConnection() {
        // Simulate WebSocket connection with polling
        this.isConnected = true;
        console.log('Real-time notifications connected (simulated)');
        
        // Start polling for notifications
        this.startPolling();
    }

    startPolling() {
        // Poll for new notifications every 10 seconds
        setInterval(() => {
            this.fetchNotifications();
        }, 10000);
        
        // Initial fetch
        this.fetchNotifications();
    }

    async fetchNotifications() {
        try {
            const response = await fetch('/api/notifications', {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.updateNotificationUI(data.data);
            }
        } catch (error) {
            console.error('Failed to fetch notifications:', error);
        }
    }

    updateNotificationUI(data) {
        const { notifications, unread_count } = data;
        
        // Update unread badge
        if (this.unreadBadge) {
            if (unread_count > 0) {
                this.unreadBadge.textContent = unread_count;
                this.unreadBadge.style.display = 'block';
            } else {
                this.unreadBadge.style.display = 'none';
            }
        }
        
        // Show new notifications
        notifications.forEach(notification => {
            if (!notification.read) {
                this.showNotification(notification);
            }
        });
    }

    showNotification(notification) {
        const notificationElement = document.createElement('div');
        notificationElement.className = 'toast show mb-2';
        notificationElement.style.minWidth = '300px';
        
        const iconClass = this.getNotificationIcon(notification.type);
        const priorityClass = this.getPriorityClass(notification.data?.priority);
        
        notificationElement.innerHTML = `
            <div class="toast-header ${priorityClass}">
                <i class="fas ${iconClass} me-2"></i>
                <strong class="me-auto">${notification.title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${notification.message}
                ${notification.data?.action_url ? 
                    `<div class="mt-2">
                        <a href="${notification.data.action_url}" class="btn btn-sm btn-primary">Xem chi tiết</a>
                    </div>` : ''
                }
            </div>
        `;
        
        this.notificationContainer.appendChild(notificationElement);
        
        // Auto remove after 10 seconds
        setTimeout(() => {
            if (notificationElement.parentNode) {
                notificationElement.remove();
            }
        }, 10000);
        
        // Mark as read when clicked
        notificationElement.addEventListener('click', () => {
            this.markAsRead(notification.id);
        });
        
        // Play notification sound (if enabled)
        this.playNotificationSound();
    }

    getNotificationIcon(type) {
        const icons = {
            'book_available': 'fa-book',
            'overdue_book': 'fa-exclamation-triangle',
            'reservation_ready': 'fa-check-circle',
            'announcement': 'fa-bullhorn',
            'test': 'fa-bell',
            'default': 'fa-info-circle'
        };
        
        return icons[type] || icons.default;
    }

    getPriorityClass(priority) {
        const classes = {
            'high': 'bg-danger text-white',
            'normal': 'bg-primary text-white',
            'low': 'bg-secondary text-white'
        };
        
        return classes[priority] || classes.normal;
    }

    async markAsRead(notificationId) {
        try {
            await fetch(`/api/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }

    async markUserOnline() {
        try {
            await fetch('/api/notifications/online', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
        } catch (error) {
            console.error('Failed to mark user as online:', error);
        }
    }

    setupHeartbeat() {
        // Send heartbeat every 2 minutes to keep connection alive
        setInterval(() => {
            if (this.isConnected) {
                this.markUserOnline();
            }
        }, 120000);
    }

    scheduleReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            setTimeout(() => {
                console.log(`Attempting to reconnect... (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
                this.connect();
            }, this.reconnectInterval);
        } else {
            console.error('Max reconnection attempts reached');
        }
    }

    playNotificationSound() {
        // Play notification sound if user has enabled it
        if (localStorage.getItem('notificationSound') === 'true') {
            try {
                const audio = new Audio('/sounds/notification.mp3');
                audio.play().catch(() => {
                    // Ignore errors if audio can't play
                });
            } catch (error) {
                // Ignore errors
            }
        }
    }

    getAuthToken() {
        // Get auth token from localStorage or cookie
        return localStorage.getItem('auth_token') || 
               this.getCookie('auth_token') || 
               document.querySelector('meta[name="auth-token"]')?.content;
    }

    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    disconnect() {
        this.isConnected = false;
        if (this.socket) {
            this.socket.close();
        }
        
        // Mark user as offline
        fetch('/api/notifications/offline', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.getAuthToken()}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).catch(() => {
            // Ignore errors
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if user is authenticated
    if (document.querySelector('meta[name="user-id"]')) {
        window.realTimeClient = new RealTimeNotificationClient();
    }
});

// Clean up on page unload
window.addEventListener('beforeunload', function() {
    if (window.realTimeClient) {
        window.realTimeClient.disconnect();
    }
});























