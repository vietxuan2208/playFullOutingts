

<style>
  /* Nút AI nổi */
  .ai-btn {
    position: fixed;
    bottom: 30px;
    right: 25px;
    background: linear-gradient(135deg, #5c7cff, #3e8ef7);
    color: white;
    padding: 16px;
    border-radius: 50%;
    font-size: 22px;
    border: none;
    cursor: pointer;
    z-index: 9999;
    box-shadow: 0 6px 15px rgba(15, 23, 42, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
  }
  .ai-btn:hover { transform: scale(1.08) translateY(-2px); }

  /* Input vùng dưới */
.chat-input {
  display: flex;
  padding: 10px 14px;
  background: #e5e7eb;
  border-top: 1px solid #d1d5db;
  gap: 8px;
}

.chat-input input {
  flex: 1;
  padding: 10px 12px;
  border-radius: 999px;
  border: 1px solid #cbd5e1;
  outline: none;
  transition: all 0.2s ease;
  background: #ffffff;
  font-size: 14px;
}

.chat-input input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
}

/* Nút Send kiểu Material */
.chat-input button {
  padding: 8px 20px;
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  color: #ffffff;
  border: none;
  border-radius: 999px;
  font-weight: 600;
  cursor: pointer;
  font-size: 14px;
  box-shadow: 0 3px 10px rgba(37, 99, 235, 0.4);
  transition: all 0.25s ease;
}

.chat-input button:hover {
  background: linear-gradient(135deg, #6366f1, #3b82f6);
  transform: translateY(-1px);
  box-shadow: 0 5px 14px rgba(37, 99, 235, 0.55);
}

  /* Chat Window */
  .chat-window {
    position: fixed;
    bottom: 90px;
    right: 25px;
    width: 420px;
    height: 600px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(15, 23, 42, 0.35);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    z-index: 9999;
  }

  .chat-window-full {
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    background: #f3f4f6;
    display: flex;
    flex-direction: column;
    z-index: 9999;
  }

  /* Header */
  .chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    background: linear-gradient(135deg, #3e8ef7, #5c7cff);
    color: white;
  }

  .chat-title { font-weight: 600; font-size: 16px; }
  .chat-subtitle { font-size: 12px; opacity: 0.85; }

  .icon-btn {
    background: rgba(255,255,255,0.18);
    border: none;
    color: white;
    width: 32px; height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 15px;
  }

  /* Body */
  .chat-body {
    flex: 1;
    padding: 12px 16px;
    overflow-y: auto;
    background: #f4f6fb;
  }

  .msg { max-width: 80%; padding: 8px 12px; border-radius: 12px; margin: 6px 0; }
  .msg-user { margin-left: auto; background: #e3f2fd; }
  .msg-bot { margin-right: auto; background: white; border: 1px solid #e5e7eb; display: flex; gap: 8px; }

  .avatar { width: 28px; height: 28px; border-radius: 50%; }

  /* Input */
  .chat-input { display: flex; padding: 10px; background: #e5e7eb; gap: 8px; }
  .chat-input input {
    flex: 1;
    padding: 10px 12px;
    border-radius: 999px;
    border: 1px solid #cbd5e1;
  }
</style>


<!-- Floating Button -->
<button class="ai-btn" id="openChatBtn">
  <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" width="26">
</button>

<!-- Chat Window -->
<div id="chatWindow" class="chat-window" style="display:none;">
  <div class="chat-header">
    <div>
      <div class="chat-title">Folk game assistant</div>
      <div class="chat-subtitle">Ask anything about tug of war, checkers, etc.</div>
    </div>

    <div class="chat-header-actions">
      <button class="icon-btn" id="toggleFull">⛶</button>
      <button class="icon-btn" id="closeChat">✕</button>
    </div>
  </div>

  <div class="chat-body" id="chatBody"></div>

  <div class="chat-input">
    <input id="chatInput" type="text" placeholder="Nhập câu hỏi…">
    <button id="sendBtn">Send</button>
  </div>
</div>


<script>
  const chatWindow = document.getElementById("chatWindow");
  const openBtn    = document.getElementById("openChatBtn");
  const closeBtn   = document.getElementById("closeChat");
  const sendBtn    = document.getElementById("sendBtn");
  const input      = document.getElementById("chatInput");
  const chatBody   = document.getElementById("chatBody");
  const toggleFull = document.getElementById("toggleFull");

  openBtn.onclick = () => { chatWindow.style.display = "flex"; openBtn.style.display = "none"; };
  closeBtn.onclick = () => { chatWindow.style.display = "none"; openBtn.style.display = "flex"; };

  toggleFull.onclick = () => {
    chatWindow.className =
      chatWindow.className === "chat-window"
        ? "chat-window-full"
        : "chat-window";
  };

  sendBtn.onclick = sendMessage;
  input.addEventListener("keyup", e => { if(e.key === "Enter") sendMessage(); });

  async function sendMessage() {
    const text = input.value.trim();
    if (!text) return;

    appendUser(text);
    input.value = "";
    scrollBottom();

    appendTyping();

    const res = await fetch("/chatbot", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({ message: text })
    });

    const data = await res.json();

    removeTyping();

    appendBot(
      data.reply ??
      data.bot ??
      data.message ??
      "Bot lỗi không trả lời được."
    );

    scrollBottom();
  }

  function appendUser(text) {
    chatBody.innerHTML += `
      <div class="msg msg-user">${text}</div>
    `;
  }

  function appendBot(text) {
    chatBody.innerHTML += `
      <div class="msg msg-bot">
        <img class="avatar" src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png">
        <span>${text}</span>
      </div>
    `;
  }

  function appendTyping() {
    chatBody.innerHTML += `
      <div class="msg msg-bot" id="typing">
        <img class="avatar" src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png">
        <span>...</span>
      </div>
    `;
  }

  function removeTyping() {
    const t = document.getElementById("typing");
    if (t) t.remove();
  }

  function scrollBottom() {
    chatBody.scrollTop = chatBody.scrollHeight;
  }
</script>



