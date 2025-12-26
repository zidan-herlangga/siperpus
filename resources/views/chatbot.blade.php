<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PerpusBot v9</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; overflow: hidden; }
        .sidebar-glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-right: 1px solid #e2e8f0; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .message-anim { animation: slideIn 0.3s ease-out forwards; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 1024px) { .sidebar-closed { transform: translateX(-100%); } }
    </style>
</head>
<body class="h-full flex overflow-hidden">

    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/30 z-30 hidden lg:hidden"></div>

    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 w-72 lg:w-80 sidebar-glass z-40 p-6 flex flex-col transition-transform duration-300 sidebar-closed lg:translate-x-0">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg"><i class="fas fa-robot"></i></div>
            <h1 class="font-bold text-xl text-slate-800 tracking-tight text-slate-800 uppercase">PerpusBot</h1>
            <button onclick="toggleSidebar()" class="lg:hidden ml-auto p-2 text-slate-400"><i class="fas fa-times"></i></button>
        </div>

        <button onclick="createNewChat()" class="w-full p-4 bg-emerald-50 text-emerald-700 rounded-2xl font-bold hover:bg-emerald-100 mb-6 transition flex items-center justify-center gap-2">
            <i class="fas fa-plus-circle"></i> Chat Baru
        </button>

        <div class="flex-1 overflow-y-auto no-scrollbar space-y-1" id="history-container">
            </div>

        <div class="mt-auto pt-4 border-t border-slate-100">
            <button onclick="clearAllData()" class="text-[10px] font-bold text-red-400 hover:text-red-600 uppercase tracking-widest px-3 p-2 mb-2 transition">Bersihkan Memori</button>
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 p-3 bg-slate-50 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-100 transition uppercase tracking-wider">
                <i class="fas fa-chevron-left"></i> Dashboard
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col bg-white relative overflow-hidden">
        <header class="h-16 lg:h-20 border-b flex items-center px-4 lg:px-10 justify-between bg-white/50 backdrop-blur sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-500 bg-slate-50 rounded-lg"><i class="fas fa-bars"></i></button>
                <div class="flex flex-col">
                    <h2 class="font-bold text-slate-800 text-sm lg:text-base" id="header-title">Chat Baru</h2>
                    <span class="text-[9px] font-bold text-emerald-500 tracking-tighter uppercase">Asisten Literasi Online</span>
                </div>
            </div>
        </header>

        <div id="chat-window" class="flex-1 overflow-y-auto p-4 lg:p-10 space-y-6 lg:space-y-8 no-scrollbar bg-slate-50/10">
            </div>

        <div class="p-4 lg:p-8 bg-white border-t border-slate-50">
            <form id="chat-form" class="max-w-4xl mx-auto flex items-end gap-3 bg-slate-100 p-1.5 lg:p-2 rounded-[2rem] shadow-inner focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all">
                <textarea id="user-input" rows="1" placeholder="Cari buku atau tanya..." class="flex-1 bg-transparent border-none focus:ring-0 px-4 py-2 lg:px-6 lg:py-3 text-sm outline-none resize-none max-h-32"></textarea>
                <button type="submit" class="w-10 h-10 lg:w-14 lg:h-14 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-lg active:scale-90 transition-all flex-shrink-0"><i class="fas fa-arrow-up"></i></button>
            </form>
        </div>
    </main>

    <script>
        const chatWindow = document.getElementById('chat-window');
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const historyContainer = document.getElementById('history-container');
        const headerTitle = document.getElementById('header-title');
        
        const KEY = 'perpusbot_v9_storage';
        let sessions = JSON.parse(localStorage.getItem(KEY)) || [];
        let currentId = null;

        document.addEventListener('DOMContentLoaded', () => {
            if(sessions.length > 0) loadSession(sessions[0].id);
            else createNewChat();
            renderHistory();
            
            userInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('sidebar-closed');
            document.getElementById('overlay').classList.toggle('hidden');
        }

        function createNewChat() {
            currentId = Date.now();
            sessions.unshift({ id: currentId, title: 'Chat Baru', messages: [] });
            save();
            loadSession(currentId);
        }

        function loadSession(id) {
            currentId = id;
            const s = sessions.find(x => x.id === id);
            headerTitle.innerText = s.title;
            chatWindow.innerHTML = '';
            s.messages.forEach(m => renderMsg(m.role, m.content, m.extra));
            renderHistory();
            if(window.innerWidth < 1024) toggleSidebar();
        }

        function renderMsg(role, content, extra = null) {
            const wrap = document.createElement('div');
            wrap.className = `flex gap-3 lg:gap-4 ${role === 'user' ? 'flex-row-reverse self-end' : ''} mb-6 message-anim`;
            const bubble = role === 'user' ? 'bg-emerald-600 text-white rounded-tr-none' : 'bg-white text-slate-700 border border-slate-100 rounded-tl-none shadow-sm';
            
            wrap.innerHTML = `
                <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-xl flex-shrink-0 flex items-center justify-center text-white ${role === 'user' ? 'bg-slate-800' : 'bg-emerald-500 shadow-md'}"><i class="fas fa-${role === 'user' ? 'user' : 'robot'} text-[10px] lg:text-xs"></i></div>
                <div class="max-w-[85%] lg:max-w-[75%]">
                    <div class="p-4 lg:p-5 rounded-2xl lg:rounded-[2rem] text-xs lg:text-sm leading-relaxed whitespace-pre-wrap shadow-sm ${bubble}">${content}</div>
                    ${role === 'bot' && extra && extra.books && extra.books.length > 0 ? renderBento(extra.books) : ''}
                </div>`;
            chatWindow.appendChild(wrap);
            chatWindow.scrollTo({ top: chatWindow.scrollHeight, behavior: 'smooth' });
        }

        function renderBento(books) {
            const cards = books.map(b => `
                <div class="flex-shrink-0 w-56 lg:w-64 bg-white border border-slate-100 rounded-[2rem] p-4 shadow-sm">
                    <img src="${b.cover_image}" class="w-full h-36 lg:h-44 object-cover rounded-2xl mb-3 shadow-inner" onerror="this.src='https://via.placeholder.com/300x400?text=No+Cover'">
                    <h4 class="text-xs lg:text-sm font-bold truncate text-slate-800">${b.title}</h4>
                    <p class="text-[10px] text-slate-400 mt-1 truncate">Oleh: ${b.author}</p>
                    <div class="mt-4 border-t pt-2 flex justify-between items-center text-[9px] font-bold">
                        <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded font-bold uppercase tracking-tighter">Rak ${b.shelf_code}</span>
                        <span class="text-slate-300">${b.stock} Eks</span>
                    </div>
                </div>`).join('');
            return `<div class="flex gap-4 overflow-x-auto mt-4 pb-2 no-scrollbar">${cards}</div>`;
        }

        function renderHistory() {
            historyContainer.innerHTML = sessions.map(s => `
                <div class="group relative flex items-center gap-2">
                    <button onclick="loadSession(${s.id})" class="flex-1 text-left p-3 rounded-xl text-xs font-medium truncate transition ${currentId === s.id ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-50'}">
                        <i class="fas fa-comment-dots mr-2 opacity-50"></i>${s.title}
                    </button>
                    <button onclick="deleteSession(event, ${s.id})" class="absolute right-2 opacity-0 group-hover:opacity-100 p-2 text-slate-400 hover:text-red-500 transition-all"><i class="fas fa-trash-alt text-[10px]"></i></button>
                </div>`).join('');
        }

        function deleteSession(e, id) {
            e.stopPropagation();
            if(!confirm('Hapus chat ini?')) return;
            sessions = sessions.filter(x => x.id !== id);
            save();
            if(currentId === id) sessions.length > 0 ? loadSession(sessions[0].id) : createNewChat();
            else renderHistory();
        }

        function save() { localStorage.setItem(KEY, JSON.stringify(sessions)); }
        function clearAllData() { if(confirm('Hapus riwayat permanen?')) { localStorage.removeItem(KEY); location.reload(); } }

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = userInput.value.trim();
            if(!msg) return;

            const session = sessions.find(s => s.id === currentId);
            if(session.messages.length === 0) { session.title = msg.substring(0, 20); renderHistory(); headerTitle.innerText = session.title; }

            renderMsg('user', msg);
            session.messages.push({ role: 'user', content: msg });
            userInput.value = ''; userInput.style.height = 'auto';

            const loader = document.createElement('div');
            loader.className = 'flex items-center gap-2 p-4 text-[11px] text-slate-400 italic animate-pulse ml-10 lg:ml-14';
            loader.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> PerpusBot sedang mencari jawaban...';
            chatWindow.appendChild(loader);

            try {
                const res = await fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                loader.remove();
                renderMsg('bot', data.reply, data);
                session.messages.push({ role: 'bot', content: data.reply, extra: data });
                save();
            } catch (err) { loader.remove(); renderMsg('bot', 'Gagal memproses. Periksa koneksi.'); }
        });
    </script>
</body>
</html>
