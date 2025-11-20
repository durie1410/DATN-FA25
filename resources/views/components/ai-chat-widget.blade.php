<!-- AI Chat Widget -->
<div id="ai-chat-widget" class="ai-chat-widget">
    <!-- Chat Button -->
    <button id="ai-chat-toggle" class="ai-chat-toggle" title="Chat với AI">
        <i class="fas fa-comments"></i>
        <span class="chat-badge" id="chat-badge" style="display: none;">1</span>
    </button>

    <!-- Chat Box -->
    <div id="ai-chat-box" class="ai-chat-box" style="display: none;">
        <!-- Header -->
        <div class="ai-chat-header">
            <div class="d-flex align-items-center">
                <div class="ai-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="ms-2">
                    <h6 class="mb-0">Trợ lý AI LIBHUB</h6>
                    <small class="text-success">
                        <i class="fas fa-circle" style="font-size: 8px;"></i> Đang hoạt động
                    </small>
                </div>
            </div>
            <div class="ai-chat-actions">
                <button class="btn btn-sm btn-link text-white" id="ai-chat-clear" title="Xóa lịch sử">
                    <i class="fas fa-trash"></i>
                </button>
                <button class="btn btn-sm btn-link text-white" id="ai-chat-minimize" title="Thu nhỏ">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <!-- Messages -->
        <div class="ai-chat-messages" id="ai-chat-messages">
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
        </div>

        <!-- Input -->
        <div class="ai-chat-input">
            <form id="ai-chat-form">
                @csrf
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="ai-chat-message" 
                           placeholder="Nhập tin nhắn..." 
                           autocomplete="off"
                           maxlength="1000">
                    <button type="submit" class="btn btn-primary" id="ai-chat-send">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
            <div class="ai-chat-suggestions">
                <button class="suggestion-btn" data-message="Tìm sách hay cho tôi">📚 Tìm sách hay</button>
                <button class="suggestion-btn" data-message="Hướng dẫn đặt hàng">🛒 Hướng dẫn đặt hàng</button>
                <button class="suggestion-btn" data-message="Phương thức thanh toán">💳 Thanh toán</button>
            </div>
        </div>
    </div>
</div>

<style>
.ai-chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}

.ai-chat-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    position: relative;
}

.ai-chat-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.chat-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4757;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.ai-chat-box {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 380px;
    height: 550px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ai-chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ai-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.ai-chat-actions {
    display: flex;
    gap: 5px;
}

.ai-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #f8f9fa;
}

.ai-message {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ai-message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.ai-message.user-message {
    flex-direction: row-reverse;
}

.ai-message.user-message .ai-message-avatar {
    background: #28a745;
}

.ai-message-content {
    flex: 1;
    max-width: 75%;
}

.ai-message.user-message .ai-message-content {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.ai-message-bubble {
    padding: 10px 14px;
    border-radius: 12px;
    word-wrap: break-word;
    white-space: pre-wrap;
}

.ai-message-bubble.ai-message-bot {
    background: white;
    color: #333;
    border: 1px solid #e0e0e0;
}

.ai-message-bubble.ai-message-user {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.ai-message-time {
    font-size: 11px;
    color: #999;
    margin-top: 4px;
}

.ai-chat-input {
    padding: 16px;
    background: white;
    border-top: 1px solid #e0e0e0;
}

.ai-chat-suggestions {
    display: flex;
    gap: 8px;
    margin-top: 8px;
    flex-wrap: wrap;
}

.suggestion-btn {
    padding: 6px 12px;
    border: 1px solid #667eea;
    background: white;
    color: #667eea;
    border-radius: 16px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.suggestion-btn:hover {
    background: #667eea;
    color: white;
}

.ai-typing {
    display: flex;
    gap: 4px;
    padding: 10px 14px;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    width: fit-content;
}

.ai-typing span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #667eea;
    animation: typing 1.4s infinite;
}

.ai-typing span:nth-child(2) {
    animation-delay: 0.2s;
}

.ai-typing span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
}

@media (max-width: 768px) {
    .ai-chat-box {
        width: calc(100vw - 40px);
        height: calc(100vh - 120px);
        bottom: 80px;
        right: 20px;
    }
}
</style>

