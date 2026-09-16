<!-- AI Chatbot Floating Widget -->
<div id="aiChatbotContainer">
    <!-- Nút tròn kích hoạt Chatbot ở góc phải -->
    <button id="chatbotToggleBtn" class="shadow-lg" title="Hỏi trợ lý thời trang Owen" aria-label="Mở hộp chat">
        <i class="fas fa-comment-dots" id="chatIconOpen"></i>
        <i class="fas fa-times d-none" id="chatIconClose"></i>
        <span class="chatbot-pulse"></span>
        <span class="chatbot-greeting-badge d-none d-md-inline-block">Hỏi Trợ lý Owen</span>
    </button>

    <!-- Cửa sổ Chatbot -->
    <div id="chatbotWindow" class="card border-0 shadow-lg d-none">
        <!-- Header -->
        <div class="chatbot-header d-flex align-items-center justify-content-between p-3 bg-dark text-white rounded-top-4">
            <div class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <div class="chatbot-avatar bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                        OW
                    </div>
                    <span class="chatbot-online-indicator"></span>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold fs-6">Trợ Lý Owen</h6>
                    <small class="text-success-light" style="font-size: 0.75rem;"><i class="fas fa-circle text-success me-1" style="font-size: 0.55rem;"></i>Owen trực tuyến 24/7</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" id="chatbotCloseBtn" aria-label="Đóng"></button>
        </div>

        <!-- Thân tin nhắn -->
        <div class="chatbot-body p-3" id="chatbotMessages">
            <!-- Tin nhắn chào mừng ban đầu -->
            <div class="chatbot-msg bot-msg mb-3">
                <div class="chatbot-bubble shadow-sm">
                    Chào bạn! Mình là <strong>Owen - Trợ lý Ảo Thời Trang</strong> 👕✨<br><br>
                    Owen có thể hỗ trợ bạn:
                    <ul class="mb-1 ps-3 small">
                        <li>Tư vấn chọn size chuẩn theo chiều cao / cân nặng</li>
                        <li>Tìm mẫu áo sơ mi, polo, thun, quần tây mới nhất</li>
                        <li>Chính sách đổi trả 30 ngày &amp; Freeship</li>
                        <li>Tra cứu tình trạng mã đơn hàng (ví dụ: <code>ORD-...</code>)</li>
                    </ul>
                    Bạn cần Owen tư vấn điều gì hôm nay ạ?
                </div>
                <small class="text-muted chatbot-time">Vừa xong</small>
            </div>

            <!-- Các nút gợi ý nhanh -->
            <div class="chatbot-quick-chips d-flex flex-wrap gap-1 mb-3" id="quickChips">
                <button type="button" class="btn btn-outline-secondary btn-sm chip-btn" onclick="sendQuickMessage('Bảng chọn size chuẩn?')">📏 Bảng size</button>
                <button type="button" class="btn btn-outline-secondary btn-sm chip-btn" onclick="sendQuickMessage('Chính sách đổi trả hàng?')">🔄 Đổi trả 30 ngày</button>
                <button type="button" class="btn btn-outline-secondary btn-sm chip-btn" onclick="sendQuickMessage('Chính sách phí ship và freeship?')">🚚 Phí ship</button>
                <button type="button" class="btn btn-outline-secondary btn-sm chip-btn" onclick="sendQuickMessage('Có mẫu áo polo nào đẹp không?')">👕 Áo Polo</button>
            </div>

            <!-- Typing Indicator (Đang gõ) -->
            <div class="chatbot-msg bot-msg d-none" id="chatbotTyping">
                <div class="chatbot-bubble typing-bubble">
                    <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                </div>
            </div>
        </div>

        <!-- Khung nhập liệu & Nút gửi -->
        <div class="chatbot-footer p-2 border-top bg-white rounded-bottom-4">
            <form id="chatbotForm" class="d-flex align-items-center gap-2" onsubmit="handleChatSubmit(event)">
                <input type="text" id="chatbotInput" class="form-control form-control-sm border-0 shadow-none px-3" 
                       placeholder="Hỏi Owen về sản phẩm, size, đơn hàng..." autocomplete="off" maxlength="300">
                <button type="submit" class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" id="chatbotSendBtn">
                    <i class="fas fa-paper-plane" style="font-size: 0.85rem;"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Container cố định góc phải màn hình */
    #aiChatbotContainer {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 9999;
        font-family: inherit;
    }

    /* Nút tròn Toggle */
    #chatbotToggleBtn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: #ffffff;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        position: relative;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    #chatbotToggleBtn:hover {
        transform: scale(1.08);
        box-shadow: 0 10px 25px rgba(220, 38, 38, 0.4);
    }

    /* Huy hiệu gọi mời */
    .chatbot-greeting-badge {
        position: absolute;
        right: 68px;
        background: #111827;
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        pointer-events: none;
    }
    .chatbot-greeting-badge::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -6px;
        transform: translateY(-50%);
        border-width: 6px 0 6px 6px;
        border-style: solid;
        border-color: transparent transparent transparent #111827;
    }

    /* Hiệu ứng pulse sóng lan tỏa */
    .chatbot-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(220, 38, 38, 0.4);
        animation: pulseEffect 2s infinite;
        z-index: -1;
    }
    @keyframes pulseEffect {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(1.45); opacity: 0; }
    }

    /* Cửa sổ Chat */
    #chatbotWindow {
        width: 360px;
        height: 480px;
        max-width: calc(100vw - 40px);
        max-height: calc(100vh - 120px);
        display: flex;
        flex-direction: column;
        border-radius: 16px;
        overflow: hidden;
        animation: chatFadeIn 0.25s ease-out;
    }
    @keyframes chatFadeIn {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .chatbot-avatar {
        width: 38px;
        height: 38px;
        font-size: 1rem;
    }
    .chatbot-online-indicator {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
        background-color: #22c55e;
        border: 2px solid #111827;
        border-radius: 50%;
    }

    /* Thân chat cuộn */
    .chatbot-body {
        flex: 1;
        overflow-y: auto;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
    }

    /* Bong bóng tin nhắn */
    .chatbot-msg {
        display: flex;
        flex-direction: column;
        max-width: 85%;
    }
    .chatbot-msg.bot-msg {
        align-self: flex-start;
    }
    .chatbot-msg.user-msg {
        align-self: flex-end;
    }

    .chatbot-bubble {
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 0.875rem;
        line-height: 1.45;
        word-wrap: break-word;
    }
    .bot-msg .chatbot-bubble {
        background-color: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 2px;
    }
    .user-msg .chatbot-bubble {
        background-color: #dc2626;
        color: #ffffff;
        border-bottom-right-radius: 2px;
    }

    .chatbot-time {
        font-size: 0.7rem;
        margin-top: 3px;
        padding: 0 4px;
    }
    .user-msg .chatbot-time {
        text-align: right;
    }

    /* Quick chips */
    .chip-btn {
        font-size: 0.75rem;
        border-radius: 12px;
        padding: 2px 8px;
        background: #ffffff;
    }
    .chip-btn:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }

    /* Typing dots */
    .typing-bubble {
        padding: 8px 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        gap: 4px;
        align-items: center;
    }
    .typing-bubble .dot {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typingDot 1.4s infinite ease-in-out;
    }
    .typing-bubble .dot:nth-child(1) { animation-delay: 0s; }
    .typing-bubble .dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-bubble .dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typingDot {
        0%, 80%, 100% { transform: scale(0.7); opacity: 0.5; }
        40% { transform: scale(1.1); opacity: 1; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chatbotToggleBtn');
    const closeBtn = document.getElementById('chatbotCloseBtn');
    const chatWindow = document.getElementById('chatbotWindow');
    const iconOpen = document.getElementById('chatIconOpen');
    const iconClose = document.getElementById('chatIconClose');
    const messagesContainer = document.getElementById('chatbotMessages');
    const chatInput = document.getElementById('chatbotInput');
    const typingIndicator = document.getElementById('chatbotTyping');

    function toggleChat() {
        const isHidden = chatWindow.classList.contains('d-none');
        if (isHidden) {
            chatWindow.classList.remove('d-none');
            iconOpen.classList.add('d-none');
            iconClose.classList.remove('d-none');
            chatInput.focus();
            scrollMessagesBottom();
        } else {
            chatWindow.classList.add('d-none');
            iconOpen.classList.remove('d-none');
            iconClose.classList.add('d-none');
        }
    }

    toggleBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    function scrollMessagesBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 50);
    }

    window.sendQuickMessage = function(text) {
        chatInput.value = text;
        handleChatSubmit(new Event('submit'));
    };

    window.handleChatSubmit = function(e) {
        if (e) e.preventDefault();
        const text = chatInput.value.trim();
        if (!text) return;

        // 1. Thêm tin nhắn của User vào giao diện
        appendMessage(text, 'user');
        chatInput.value = '';

        // Ẩn quick chips sau khi đã hỏi
        const chips = document.getElementById('quickChips');
        if (chips) chips.style.display = 'none';

        // 2. Hiển thị typing indicator
        typingIndicator.classList.remove('d-none');
        scrollMessagesBottom();

        // 3. Gửi request POST tới route /chatbot/message
        fetch("{{ route('chatbot.reply') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: text })
        })
        .then(response => response.json())
        .then(data => {
            typingIndicator.classList.add('d-none');
            const reply = data.reply || 'Dạ xin lỗi bạn, có chút gián đoạn kết nối. Bạn vui lòng thử lại sau giây lát nha!';
            appendMessage(reply, 'bot');
        })
        .catch(err => {
            console.error('Chatbot error:', err);
            typingIndicator.classList.add('d-none');
            appendMessage('Dạ hiện tại em đang gặp chút lỗi mạng, bạn có thể gọi hotline <strong>1900 8079</strong> để được hỗ trợ nhanh nhất nhé!', 'bot');
        });
    };

    function appendMessage(content, sender) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `chatbot-msg ${sender}-msg mb-3`;
        
        const now = new Date();
        const timeStr = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;

        msgDiv.innerHTML = `
            <div class="chatbot-bubble shadow-sm">${content}</div>
            <small class="text-muted chatbot-time">${timeStr}</small>
        `;

        messagesContainer.insertBefore(msgDiv, typingIndicator);
        scrollMessagesBottom();
    }
});
</script>
