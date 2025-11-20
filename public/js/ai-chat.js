// AI Chat Widget JavaScript
(function() {
    'use strict';

    const chatWidget = {
        isOpen: false,
        isTyping: false,

        init() {
            this.bindEvents();
            this.loadHistory();
        },

        bindEvents() {
            // Toggle chat box
            document.getElementById('ai-chat-toggle') ? .addEventListener('click', () => {
                this.toggleChat();
            });

            // Minimize chat
            document.getElementById('ai-chat-minimize') ? .addEventListener('click', () => {
                this.toggleChat();
            });

            // Clear history
            document.getElementById('ai-chat-clear') ? .addEventListener('click', () => {
                this.clearHistory();
            });

            // Send message
            document.getElementById('ai-chat-form') ? .addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendMessage();
            });

            // Suggestion buttons
            document.querySelectorAll('.suggestion-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const message = btn.getAttribute('data-message');
                    document.getElementById('ai-chat-message').value = message;
                    this.sendMessage();
                });
            });
        },

        toggleChat() {
            const chatBox = document.getElementById('ai-chat-box');
            const chatBadge = document.getElementById('chat-badge');

            this.isOpen = !this.isOpen;

            if (this.isOpen) {
                chatBox.style.display = 'flex';
                chatBadge.style.display = 'none';
                this.scrollToBottom();
            } else {
                chatBox.style.display = 'none';
            }
        },

        async loadHistory() {
            try {
                const response = await fetch('/ai-chat/history', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.messages.length > 0) {
                        // Clear default message
                        const messagesContainer = document.getElementById('ai-chat-messages');
                        messagesContainer.innerHTML = '';

                        // Add history messages
                        data.messages.forEach(msg => {
                            this.addMessage(msg.message, msg.role === 'user', msg.timestamp, false);
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading chat history:', error);
            }
        },

        async sendMessage() {
            const input = document.getElementById('ai-chat-message');
            const message = input.value.trim();

            if (!message) return;

            // Add user message to UI
            this.addMessage(message, true);
            input.value = '';

            // Show typing indicator
            this.showTyping();

            try {
                const response = await fetch('/ai-chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                // Remove typing indicator
                this.hideTyping();

                if (data.success) {
                    this.addMessage(data.message, false, data.timestamp);
                } else {
                    this.addMessage('Xin lỗi, tôi đang gặp sự cố. Vui lòng thử lại sau.', false);
                }
            } catch (error) {
                console.error('Error sending message:', error);
                this.hideTyping();
                this.addMessage('Không thể kết nối đến server. Vui lòng thử lại.', false);
            }
        },

        addMessage(message, isUser, timestamp = null, scroll = true) {
            const messagesContainer = document.getElementById('ai-chat-messages');
            const time = timestamp || new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });

            const messageHTML = `
                <div class="ai-message ${isUser ? 'user-message' : ''}">
                    <div class="ai-message-avatar">
                        <i class="fas fa-${isUser ? 'user' : 'robot'}"></i>
                    </div>
                    <div class="ai-message-content">
                        <div class="ai-message-bubble ai-message-${isUser ? 'user' : 'bot'}">
                            ${this.escapeHtml(message)}
                        </div>
                        <div class="ai-message-time">${time}</div>
                    </div>
                </div>
            `;

            messagesContainer.insertAdjacentHTML('beforeend', messageHTML);

            if (scroll) {
                this.scrollToBottom();
            }
        },

        showTyping() {
            const messagesContainer = document.getElementById('ai-chat-messages');
            const typingHTML = `
                <div class="ai-message" id="typing-indicator">
                    <div class="ai-message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="ai-message-content">
                        <div class="ai-typing">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            `;
            messagesContainer.insertAdjacentHTML('beforeend', typingHTML);
            this.scrollToBottom();
        },

        hideTyping() {
            const typingIndicator = document.getElementById('typing-indicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        },

        async clearHistory() {
            if (!confirm('Bạn có chắc muốn xóa toàn bộ lịch sử chat?')) {
                return;
            }

            try {
                const response = await fetch('/ai-chat/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const messagesContainer = document.getElementById('ai-chat-messages');
                    messagesContainer.innerHTML = `
                        <div class="ai-message">
                            <div class="ai-message-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="ai-message-content">
                                <div class="ai-message-bubble ai-message-bot">
                                    Xin chào! Tôi là trợ lý AI của Thư Viện LIBHUB. Tôi có thể giúp gì cho bạn hôm nay? 😊
                                </div>
                                <div class="ai-message-time">Bây giờ</div>
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error clearing history:', error);
                alert('Không thể xóa lịch sử. Vui lòng thử lại.');
            }
        },

        scrollToBottom() {
            const messagesContainer = document.getElementById('ai-chat-messages');
            setTimeout(() => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 100);
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML.replace(/\n/g, '<br>');
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => chatWidget.init());
    } else {
        chatWidget.init();
    }
})();