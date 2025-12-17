<style>
    #support-chat-widget {
        position: fixed;
        right: 22px;
        bottom: 22px;
        z-index: 9999;
        font-family: Arial, sans-serif;
        color: #1f2933;
    }

    #support-chat-widget .chat-launcher {
        display: flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #fff;
        border: none;
        border-radius: 999px;
        padding: 12px 16px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.16);
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    #support-chat-widget .chat-launcher:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.18);
    }

    #support-chat-widget .chat-panel {
        width: 340px;
        max-width: calc(100vw - 20px);
        height: 470px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        display: none;
        flex-direction: column;
        position: absolute;
        right: 0;
        bottom: 64px;
        border: 1px solid #e5e7eb;
    }

    #support-chat-widget .chat-panel.open {
        display: flex;
    }

    #support-chat-widget .chat-header {
        padding: 14px 16px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #support-chat-widget .chat-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    #support-chat-widget .chat-header-status {
        font-size: 11px;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 2px;
    }

    #support-chat-widget .chat-header-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.25);
    }

    #support-chat-widget .chat-close {
        background: rgba(255, 255, 255, 0.16);
        border: none;
        color: #fff;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    #support-chat-widget .chat-close:hover {
        background: rgba(255, 255, 255, 0.24);
    }

    #support-chat-widget .chat-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
    }

    #support-chat-widget .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    #support-chat-widget .chat-message {
        max-width: 88%;
        padding: 10px 12px;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        font-size: 14px;
        line-height: 1.4;
    }

    #support-chat-widget .chat-message.from-support {
        align-self: flex-start;
        background: #e0ebff;
        border: 1px solid #c7d8ff;
    }

    #support-chat-widget .chat-message.from-user {
        align-self: flex-end;
        background: #dbeafe;
        border: 1px solid #bfdbfe;
    }

    #support-chat-widget .chat-message small {
        display: block;
        margin-top: 6px;
        font-size: 11px;
        color: #6b7280;
    }

    #support-chat-widget .chat-form {
        border-top: 1px solid #e5e7eb;
        padding: 10px 12px;
        background: #fff;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    #support-chat-widget .chat-form .guest-fields {
        display: grid;
        grid-template-columns: 1fr;
        gap: 6px;
    }

    #support-chat-widget input,
    #support-chat-widget textarea {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    #support-chat-widget input:focus,
    #support-chat-widget textarea:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    #support-chat-widget textarea {
        min-height: 72px;
        resize: vertical;
    }

    #support-chat-widget .chat-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
    }

    #support-chat-widget .chat-submit {
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.1s ease;
    }

    #support-chat-widget .chat-submit:hover {
        background: #1d4ed8;
    }

    #support-chat-widget .chat-submit:active {
        transform: translateY(1px);
    }

    #support-chat-widget .chat-status {
        font-size: 12px;
        color: #6b7280;
        text-align: left;
        min-height: 18px;
    }

    @media (max-width: 480px) {
        #support-chat-widget {
            right: 10px;
            bottom: 10px;
        }

        #support-chat-widget .chat-panel {
            width: calc(100vw - 20px);
            height: 70vh;
        }
    }
</style>

<div id="support-chat-widget" aria-live="polite"></div>

<script>
    (function () {
        if (window.__supportChatLoaded) return;
        window.__supportChatLoaded = true;

        const chatConfig = {
            fetchUrl: @json(route('chat.messages.index')),
            postUrl: @json(route('chat.messages.store')),
            csrfToken: @json(csrf_token()),
            isAuthenticated: @json(auth()->check()),
            userName: @json(auth()->user()->name ?? null),
            userEmail: @json(auth()->user()->email ?? null),
        };

        const root = document.getElementById('support-chat-widget');
        if (!root) return;

        root.innerHTML = `
            <button class="chat-launcher" type="button" aria-label="Mở hộp chat hỗ trợ">
                💬 <span>Hỗ trợ</span>
            </button>
            <div class="chat-panel" role="dialog" aria-label="Chat hỗ trợ thư viện">
                <div class="chat-header">
                    <div>
                        <h4>Hỗ trợ thư viện</h4>
                        <div class="chat-header-status">
                            <span class="chat-header-dot"></span>
                            <span id="chat-online-text">Đang trực tuyến</span>
                        </div>
                    </div>
                    <button class="chat-close" type="button" aria-label="Đóng chat">×</button>
                </div>
                <div class="chat-body">
                    <div class="chat-messages" id="chat-messages">
                        <div class="chat-message from-support">
                            Xin chào 👋<br>
                            Bạn cần hỗ trợ gì? Hãy để lại tin nhắn, chúng tôi sẽ phản hồi sớm nhất.
                            <small>Hệ thống</small>
                        </div>
                    </div>
                    <form class="chat-form" id="chat-form">
                        ${chatConfig.isAuthenticated ? '' : `
                            <div class="guest-fields">
                                <input type="text" name="name" placeholder="Tên của bạn (tuỳ chọn)" aria-label="Tên của bạn">
                                <input type="email" name="email" placeholder="Email liên hệ (tuỳ chọn)" aria-label="Email của bạn">
                            </div>
                        `}
                        <textarea name="message" placeholder="Nhập tin nhắn..." required aria-label="Nội dung tin nhắn"></textarea>
                        <div class="chat-actions">
                            <div class="chat-status" id="chat-status"></div>
                            <button class="chat-submit" type="submit">Gửi</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        const launcher = root.querySelector('.chat-launcher');
        const panel = root.querySelector('.chat-panel');
        const closeBtn = root.querySelector('.chat-close');
        const form = root.querySelector('#chat-form');
        const messagesEl = root.querySelector('#chat-messages');
        const statusEl = root.querySelector('#chat-status');
        const messageInput = form?.querySelector('textarea[name="message"]');
        const nameInput = form?.querySelector('input[name="name"]');
        const emailInput = form?.querySelector('input[name="email"]');
        let hasLoaded = false;
        let isSubmitting = false;

        function togglePanel(show) {
            const shouldShow = typeof show === 'boolean' ? show : !panel.classList.contains('open');
            panel.classList.toggle('open', shouldShow);
            if (shouldShow && !hasLoaded) {
                loadMessages();
            }
        }

        launcher?.addEventListener('click', () => togglePanel());
        closeBtn?.addEventListener('click', () => togglePanel(false));

        async function loadMessages() {
            statusEl.textContent = 'Đang tải cuộc trò chuyện...';
            try {
                const res = await fetch(chatConfig.fetchUrl, {
                    method: 'GET',
                    credentials: 'same-origin',
                });
                const data = await res.json();
                renderMessages(data.messages || []);
                statusEl.textContent = 'Kết nối hỗ trợ';
                hasLoaded = true;
            } catch (err) {
                console.error('Load chat messages error', err);
                statusEl.textContent = 'Không tải được tin nhắn. Thử lại sau.';
            }
        }

        function renderMessages(messages) {
            messagesEl.innerHTML = '';
            if (!messages || messages.length === 0) {
                messagesEl.innerHTML = `
                    <div class="chat-message from-support">
                        Chào bạn! Hãy để lại câu hỏi, chúng tôi sẽ phản hồi sớm.
                        <small>Hỗ trợ</small>
                    </div>
                `;
                return;
            }

            messages.forEach(appendMessage);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        function appendMessage(msg) {
            const wrapper = document.createElement('div');
            const isSupport = msg.sender_type === 'support';
            const isUser = msg.sender_type === 'user' || msg.sender_type === 'guest';
            wrapper.className = `chat-message ${isSupport ? 'from-support' : 'from-user'}`;
            const sender = msg.sender_name || (isSupport ? 'Hỗ trợ' : 'Bạn');
            const time = msg.created_at ? new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
            wrapper.innerHTML = `
                <div>${msg.message ? escapeHtml(msg.message) : ''}</div>
                <small>${sender}${time ? ' • ' + time : ''}</small>
            `;
            messagesEl.appendChild(wrapper);
        }

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
                .replace(/\n/g, '<br>');
        }

        // Submit on Enter, newline with Shift+Enter
        messageInput?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form?.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
            }
        });

        form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (isSubmitting) return;

            const message = (messageInput?.value || '').trim();
            if (!message) {
                statusEl.textContent = 'Vui lòng nhập nội dung.';
                return;
            }

            const payload = {
                message,
            };

            if (!chatConfig.isAuthenticated) {
                if (nameInput?.value) payload.name = nameInput.value.trim();
                if (emailInput?.value) payload.email = emailInput.value.trim();
            }

            isSubmitting = true;
            statusEl.textContent = 'Đang gửi...';

            try {
                const res = await fetch(chatConfig.postUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': chatConfig.csrfToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload),
                });

                if (!res.ok) {
                    throw new Error('Request failed');
                }

                const data = await res.json();
                if (data.message) {
                    appendMessage(data.message);
                    messagesEl.scrollTop = messagesEl.scrollHeight;
                }
                messageInput.value = '';
                statusEl.textContent = 'Đã gửi. Chúng tôi sẽ trả lời sớm.';
            } catch (err) {
                console.error('Send chat message error', err);
                statusEl.textContent = 'Gửi thất bại. Thử lại.';
            } finally {
                isSubmitting = false;
            }
        });
    })();
</script>

