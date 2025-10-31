@extends('layouts.app')

@section('title', 'Chatbot - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center text-green-600 hover:text-green-700 mb-4">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Chatbot Perpustakaan</h1>
            <p class="text-gray-600">Tanya tentang buku, peminjaman, dan informasi perpustakaan</p>
        </div>

        {{-- Chat Container --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-4xl mx-auto">
            {{-- Chat Header --}}
            <div class="bg-green-600 text-white px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-robot text-xl mr-3"></i>
                        <div>
                            <h2 class="font-semibold text-lg">Asisten Perpustakaan</h2>
                            <p class="text-green-100 text-sm">Siap membantu Anda</p>
                        </div>
                    </div>
                    <button onclick="clearChat()" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded text-sm transition-colors">
                        Hapus Chat
                    </button>
                </div>
            </div>

            {{-- Chat Messages --}}
            <div id="chatbot-chat" class="h-96 overflow-y-auto p-4 bg-gray-50 space-y-4">
                {{-- Messages will be loaded here --}}
            </div>

            {{-- Quick Suggestions --}}
            <div class="border-t border-gray-200 bg-white px-4 py-3">
                <div class="flex flex-wrap gap-2">
                    <button onclick="sendSuggestion('Apa buku terbaru di perpustakaan?')" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-2 rounded text-sm transition-colors">
                        📚 Buku Terbaru
                    </button>
                    <button onclick="sendSuggestion('Bagaimana cara memperpanjang peminjaman buku?')" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-2 rounded text-sm transition-colors">
                        ⏰ Perpanjangan
                    </button>
                    <button onclick="sendSuggestion('Siapa penulis buku Bumi Manusia?')" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-2 rounded text-sm transition-colors">
                        ✍️ Penulis Buku
                    </button>
                    <button onclick="sendSuggestion('Apakah ada buku novel romantis?')" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-2 rounded text-sm transition-colors">
                        💞 Novel Romantis
                    </button>
                </div>
            </div>

            {{-- Input Area --}}
            <div class="border-t border-gray-200 bg-white p-4">
                <div class="flex gap-2">
                    <input type="text" id="chatbot-input" 
                           class="flex-1 border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                           placeholder="Ketik pesan Anda..." 
                           onkeypress="if(event.key==='Enter') sendMessage()">
                    <button onclick="sendMessage()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-medium transition-colors">
                        Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chat-message {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 12px;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .bot-message {
        background: #e8f5e9;
        color: #1b5e20;
        align-self: flex-start;
        border-top-left-radius: 4px;
    }

    .user-message {
        background: #2e7d32;
        color: white;
        align-self: flex-end;
        border-top-right-radius: 4px;
    }

    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        background: #1b5e20;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typing {
        0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
        40% { transform: scale(1); opacity: 1; }
    }
</style>

<script>
const chat = document.getElementById('chatbot-chat');

// Load chat history from localStorage
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

        const reply = data.reply ? data.reply.replace(/\n/g, "<br>") : "Maaf, saya tidak bisa menjawab pertanyaan itu saat ini.";
        appendMessage('bot', reply, true);

    } catch (err) {
        typing.remove();
        appendMessage('bot', 'Maaf, terjadi gangguan koneksi. Silakan coba lagi.');
    }

    saveChat();
}

function appendMessage(role, text, isHTML = false) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;
    
    const messageContent = document.createElement('div');
    messageContent.className = `chat-message ${role === 'bot' ? 'bot-message' : 'user-message'}`;
    messageContent.innerHTML = isHTML ? text : text.replace(/\n/g, '<br>');
    
    messageDiv.appendChild(messageContent);
    chat.appendChild(messageDiv);
    chat.scrollTop = chat.scrollHeight;
    saveChat();
}

function appendTyping() {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex justify-start';
    
    const typingDiv = document.createElement('div');
    typingDiv.className = 'chat-message bot-message';
    typingDiv.innerHTML = `
        <div class="typing-indicator">
            Bot sedang mengetik
            <span class="typing-dot"></span>
            <span class="typing-dot"></span>
            <span class="typing-dot"></span>
        </div>
    `;
    
    messageDiv.appendChild(typingDiv);
    chat.appendChild(messageDiv);
    chat.scrollTop = chat.scrollHeight;
    return typingDiv;
}

function sendSuggestion(text) {
    document.getElementById('chatbot-input').value = text;
    sendMessage();
}

// Save chat history to localStorage
function saveChat() {
    localStorage.setItem('chat_history', chat.innerHTML);
}

// Clear chat history
function clearChat() {
    if (confirm("Yakin ingin menghapus seluruh riwayat chat?")) {
        localStorage.removeItem('chat_history');
        chat.innerHTML = '';
        appendMessage('bot', 'Riwayat chat telah dihapus. Hai 👋, ada yang bisa saya bantu?');
    }
}
</script>
@endsection