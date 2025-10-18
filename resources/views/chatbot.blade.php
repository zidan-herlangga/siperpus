<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Perpustakaan</title>
    <style>
        * { box-sizing: border-box; }

        body {
            background: #f1f8e9;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .chatbot-page {
            width: 95%;
            max-width: 900px;
            height: 90vh;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 2px solid #c8e6c9;
        }

        .chatbot-header {
            background: linear-gradient(90deg, #2e7d32, #43a047);
            color: white;
            padding: 16px 20px;
            font-size: 20px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot-user {
            font-size: 16px;
            background: rgba(255,255,255,0.15);
            padding: 6px 14px;
            border-radius: 20px;
        }

        .chatbot-chat {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #f9fdf9;
        }

        .chatbot-msg {
            padding: 12px 16px;
            border-radius: 16px;
            max-width: 75%;
            line-height: 1.5;
            animation: fadeIn 0.3s ease-in;
            word-wrap: break-word;
            font-size: 15px;
        }

        .chatbot-msg-bot {
            background: #e8f5e9;
            color: #1b5e20;
            align-self: flex-start;
            border-top-left-radius: 0;
        }

        .chatbot-msg-user {
            background: linear-gradient(135deg, #2e7d32, #43a047);
            color: white;
            align-self: flex-end;
            border-top-right-radius: 0;
        }

        .chatbot-input-area {
            display: flex;
            border-top: 1px solid #c8e6c9;
            background: #f1f8e9;
            padding: 10px;
        }

        .chatbot-input {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: white;
            font-size: 16px;
            outline: none;
        }

        .chatbot-btn-send {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 0 22px;
            margin-left: 10px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .chatbot-btn-send:hover { background: #1b5e20; }

        .chatbot-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px 16px;
            border-top: 1px solid #c8e6c9;
            background: #f1f8e9;
        }

        .chatbot-suggest-btn {
            background: #c8e6c9;
            color: #1b5e20;
            border: none;
            padding: 6px 14px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.2s;
            font-size: 14px;
        }

        .chatbot-suggest-btn:hover {
            background: #2e7d32;
            color: white;
        }

        .chatbot-clear {
            background: transparent;
            color: white;
            border: 1px solid rgba(255,255,255,0.6);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
        }

        .chatbot-clear:hover {
            background: rgba(255,255,255,0.2);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="chatbot-page">
    <div class="chatbot-header">
        <a href="{{ route('student.dashboard') }}" style="text-decoration: none; color: white; font-size: 24px; margin-right: 10px;">
            ←
        </a>

        Hi! {{ Auth::guard('student')->user()->name, 'Pengguna' }}

        <div>
            <button class="chatbot-clear" onclick="clearChat()">Hapus Chat</button>
        </div>
    </div>

    <div id="chatbot-chat" class="chatbot-chat"></div>

    <div class="chatbot-suggestions">
        <button class="chatbot-suggest-btn" onclick="sendSuggestion('Apa buku terbaru di perpustakaan?')">📚 Buku Terbaru</button>
        <button class="chatbot-suggest-btn" onclick="sendSuggestion('Bagaimana cara memperpanjang peminjaman buku?')">⏰ Perpanjangan</button>
        <button class="chatbot-suggest-btn" onclick="sendSuggestion('Siapa penulis buku Bumi Manusia?')">✍️ Penulis Buku</button>
        <button class="chatbot-suggest-btn" onclick="sendSuggestion('Apakah ada buku novel romantis?')">💞 Novel Romantis</button>
    </div>

    <div class="chatbot-input-area">
        <input type="text" id="chatbot-input" class="chatbot-input" placeholder="Ketik pesan..." onkeypress="if(event.key==='Enter') sendMessage()">
        <button class="chatbot-btn-send" onclick="sendMessage()">Kirim</button>
    </div>
</div>

<script>
const chat = document.getElementById('chatbot-chat');

// 🔹 Muat riwayat chat dari localStorage saat halaman dibuka
window.onload = () => {
    const savedChat = localStorage.getItem('chat_history');
    if (savedChat) {
        chat.innerHTML = savedChat;
    } else {
        appendMessage('bot', 'Hai 👋, saya asisten perpustakaanmu! Silakan tanya tentang buku, penulis, atau cara peminjaman 📚');
    }
    chat.scrollTop = chat.scrollHeight;
};

async function sendMessage() {
    const input = document.getElementById('chatbot-input');
    const message = input.value.trim();
    if (!message) return;

    appendMessage('user', message);
    input.value = '';

    const typing = appendTyping();

    try {
        const res = await fetch("/api/chatbot", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message }),
        });

        if (!res.ok) throw new Error("Server error");
        const data = await res.json();
        typing.remove();

        const reply = data.reply ? data.reply.replace(/\n/g, "<br>") : "(Tidak ada respons)";
        appendMessage('bot', reply, true);

    } catch (err) {
        typing.remove();
        appendMessage('bot', '(Gagal terhubung ke server)');
    }

    saveChat();
}

function appendMessage(role, text, isHTML = false) {
    const msg = document.createElement('div');
    msg.classList.add('chatbot-msg', role === 'bot' ? 'chatbot-msg-bot' : 'chatbot-msg-user');
    msg.innerHTML = isHTML ? text : text.replace(/\n/g, '<br>');
    chat.appendChild(msg);
    chat.scrollTop = chat.scrollHeight;
    saveChat();
}

function appendTyping() {
    const typing = document.createElement('div');
    typing.classList.add('chatbot-msg', 'chatbot-msg-bot');
    typing.innerHTML = 'Bot sedang mengetik<span class="chatbot-typing-dot"></span><span class="chatbot-typing-dot"></span><span class="chatbot-typing-dot"></span>';
    chat.appendChild(typing);
    chat.scrollTop = chat.scrollHeight;
    return typing;
}

function sendSuggestion(text) {
    document.getElementById('chatbot-input').value = text;
    sendMessage();
}

// 🔹 Simpan riwayat chat ke localStorage
function saveChat() {
    localStorage.setItem('chat_history', chat.innerHTML);
}

// 🔹 Hapus riwayat chat
function clearChat() {
    if (confirm("Yakin ingin menghapus seluruh riwayat chat?")) {
        localStorage.removeItem('chat_history');
        chat.innerHTML = '';
        appendMessage('bot', 'Riwayat chat telah dihapus. Hai 👋, mau tanya tentang buku apa hari ini?');
    }
}
</script>
</body>
</html>
