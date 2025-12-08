<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PerpusBot | Asisten Perpustakaan Digital</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Asisten pintar untuk mencari buku di perpustakaan digital">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@32,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }
        
        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .animate-bounce-slow {
            animation: bounce 1.4s infinite;
        }
        
        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
                opacity: 0.5;
            }
            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #22c55e 0%, #14532d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .dark .glass-effect {
            background: rgba(17, 24, 39, 0.9);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary-50 via-emerald-50 to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 font-sans antialiased transition-colors duration-300">
    
    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 bottom-0 w-80 bg-white dark:bg-gray-800 shadow-2xl z-50 transform -translate-x-full transition-transform duration-300 flex flex-col">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-500 to-emerald-600">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Riwayat Chat</h2>
                    <p class="text-sm text-primary-100 mt-1">Percakapan Anda</p>
                </div>
                <button onclick="toggleSidebar()" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>
        </div>
        
        <!-- History List -->
        <div id="history-list" class="flex-1 overflow-y-auto p-4 space-y-2 scrollbar-thin">
            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                <span class="material-symbols-rounded text-5xl opacity-30">history</span>
                <p class="mt-2 text-sm">Belum ada riwayat chat</p>
            </div>
        </div>
        
        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
            <button onclick="clearHistory()" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-xl font-medium transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-rounded">delete_sweep</span>
                Hapus Semua Riwayat
            </button>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="glass-effect border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left: Menu Button -->
                    <button onclick="toggleSidebar()" class="p-2 rounded-xl hover:bg-primary-100 dark:hover:bg-gray-700 transition-colors">
                        <span class="material-symbols-rounded text-gray-700 dark:text-gray-300">menu</span>
                    </button>
                    
                    <!-- Center: Logo -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white text-xl">🤖</span>
                        </div>
                        <div class="hidden sm:block">
                            <h1 class="text-lg font-bold gradient-text">PerpusBot</h1>
                            <p class="text-xs text-gray-600 dark:text-gray-400">AI Assistant</p>
                        </div>
                    </div>
                    
                    <!-- Right: Actions -->
                    <div class="flex items-center gap-2">
                        <button id="theme-toggle" onclick="toggleTheme()" class="p-2 rounded-xl hover:bg-primary-100 dark:hover:bg-gray-700 transition-colors">
                            <span class="material-symbols-rounded text-gray-700 dark:text-gray-300" id="theme-icon">light_mode</span>
                        </button>
                        <button onclick="deleteAllChats()" class="p-2 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                            <span class="material-symbols-rounded text-red-600 dark:text-red-400">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Chat Container -->
        <main class="flex-1 overflow-y-auto pb-32 scrollbar-thin">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                
                <!-- Welcome Section -->
                <div id="welcome-section" class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary-500 to-emerald-600 rounded-3xl shadow-2xl mb-6 animate-bounce">
                        <span class="text-4xl">👋</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-bold mb-3 gradient-text">
                        Halo, {{ $student->name }}!
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                        Ada yang bisa saya bantu hari ini?
                    </p>
                    
                    <!-- Suggestions -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-8">
                        <button onclick="sendSuggestion('Cari buku Laskar Pelangi')" class="group bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 border-2 border-gray-200 dark:border-gray-700 hover:border-primary-400 rounded-2xl p-4 transition-all duration-300 hover:scale-105 hover:shadow-lg text-left">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-primary-200 transition-colors">
                                    <span class="material-symbols-rounded text-primary-600 dark:text-primary-400">auto_stories</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200 text-sm">Cari Buku</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Laskar Pelangi</p>
                                </div>
                            </div>
                        </button>
                        
                        <button onclick="sendSuggestion('Buku kategori Sains')" class="group bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 border-2 border-gray-200 dark:border-gray-700 hover:border-primary-400 rounded-2xl p-4 transition-all duration-300 hover:scale-105 hover:shadow-lg text-left">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                                    <span class="material-symbols-rounded text-blue-600 dark:text-blue-400">science</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200 text-sm">Kategori</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Buku Sains</p>
                                </div>
                            </div>
                        </button>
                        
                        <button onclick="sendSuggestion('Jam operasional perpustakaan')" class="group bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 border-2 border-gray-200 dark:border-gray-700 hover:border-primary-400 rounded-2xl p-4 transition-all duration-300 hover:scale-105 hover:shadow-lg text-left">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-amber-200 transition-colors">
                                    <span class="material-symbols-rounded text-amber-600 dark:text-amber-400">schedule</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200 text-sm">Informasi</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jam Operasional</p>
                                </div>
                            </div>
                        </button>
                        
                        <button onclick="sendSuggestion('Rekomendasi buku teknologi')" class="group bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 border-2 border-gray-200 dark:border-gray-700 hover:border-primary-400 rounded-2xl p-4 transition-all duration-300 hover:scale-105 hover:shadow-lg text-left">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-purple-200 transition-colors">
                                    <span class="material-symbols-rounded text-purple-600 dark:text-purple-400">lightbulb</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200 text-sm">Rekomendasi</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Buku Teknologi</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
                
                <!-- Messages Container -->
                <div id="messages-container" class="space-y-6"></div>
            </div>
        </main>
        
        <!-- Input Area -->
        <div class="fixed bottom-0 left-0 right-0 glass-effect border-t border-gray-200 dark:border-gray-700 z-20">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <form id="chat-form" class="flex gap-3 items-end">
                    <div class="flex-1 relative">
                        <textarea 
                            id="message-input" 
                            rows="1"
                            placeholder="Ketik pesan Anda di sini..."
                            maxlength="500"
                            class="w-full px-4 py-3 pr-12 rounded-2xl border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:border-primary-500 focus:ring-4 focus:ring-primary-100 dark:focus:ring-primary-900 outline-none resize-none transition-all duration-200"
                            style="min-height: 52px; max-height: 150px;"
                        ></textarea>
                        <div class="absolute right-3 bottom-3 text-xs text-gray-400 dark:text-gray-500">
                            <span id="char-count">0</span>/500
                        </div>
                    </div>
                    <button 
                        type="submit" 
                        id="send-button"
                        class="bg-gradient-to-r from-primary-500 to-emerald-600 hover:from-primary-600 hover:to-emerald-700 text-white p-3 rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                    >
                        <span class="material-symbols-rounded">send</span>
                    </button>
                </form>
                <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-3">
                    PerpusBot dapat membuat kesalahan. Periksa informasi penting.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Constants
        const API_URL = "{{ route('chat.send') }}";
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        
        // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const welcomeSection = document.getElementById('welcome-section');
        const messagesContainer = document.getElementById('messages-container');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');
        const charCount = document.getElementById('char-count');
        const themeIcon = document.getElementById('theme-icon');
        const historyList = document.getElementById('history-list');
        
        // State
        let chatHistory = [];
        let currentSessionId = Date.now();
        let isTyping = false;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadChatHistory();
            applyTheme();
            setupEventListeners();
            autoResizeTextarea();
        });
        
        // Event Listeners
        function setupEventListeners() {
            chatForm.addEventListener('submit', handleSubmit);
            messageInput.addEventListener('input', () => {
                charCount.textContent = messageInput.value.length;
                autoResizeTextarea();
            });
            
            messageInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    chatForm.dispatchEvent(new Event('submit'));
                }
            });
        }
        
        // Auto-resize textarea
        function autoResizeTextarea() {
            messageInput.style.height = 'auto';
            messageInput.style.height = Math.min(messageInput.scrollHeight, 150) + 'px';
        }
        
        // Toggle Sidebar
        function toggleSidebar() {
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            }
        }
        
        // Toggle Theme
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                themeIcon.textContent = 'light_mode';
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                themeIcon.textContent = 'dark_mode';
                localStorage.setItem('theme', 'dark');
            }
        }
        
        function applyTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                themeIcon.textContent = 'dark_mode';
            }
        }
        
        // Send Suggestion
        function sendSuggestion(text) {
            messageInput.value = text;
            chatForm.dispatchEvent(new Event('submit'));
        }
        
        // Handle Submit
        async function handleSubmit(e) {
            e.preventDefault();
            
            if (isTyping) return;
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Hide welcome section
            if (welcomeSection) {
                welcomeSection.style.display = 'none';
            }
            
            // Add user message
            addMessage(message, 'user');
            
            // Clear input
            messageInput.value = '';
            charCount.textContent = '0';
            messageInput.style.height = 'auto';
            messageInput.focus();
            
            // Show typing indicator
            const typingId = showTypingIndicator();
            
            try {
                isTyping = true;
                sendButton.disabled = true;
                
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message })
                });
                
                const data = await response.json();
                
                removeTypingIndicator(typingId);
                
                if (data.success) {
                    addBotResponse(data);
                    saveToHistory(message, data);
                } else {
                    addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
                }
                
            } catch (error) {
                console.error('Error:', error);
                removeTypingIndicator(typingId);
                addMessage('Maaf, koneksi ke server gagal. Periksa koneksi internet Anda.', 'bot');
            } finally {
                isTyping = false;
                sendButton.disabled = false;
            }
        }
        
        // Add Message
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex gap-3 animate-slide-in ${sender === 'user' ? 'justify-end' : 'justify-start'}`;
            
            const content = `
                ${sender === 'bot' ? `
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-white text-lg">🤖</span>
                    </div>
                ` : ''}
                
                <div class="max-w-[75%] sm:max-w-[60%]">
                    <div class="${sender === 'user' 
                        ? 'bg-gradient-to-r from-primary-500 to-emerald-600 text-white' 
                        : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700'
                    } rounded-2xl px-4 py-3 shadow-lg">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap">${text}</p>
                    </div>
                    ${sender === 'bot' ? `
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 ml-2">
                            ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                        </p>
                    ` : ''}
                </div>
                
                ${sender === 'user' ? `
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-white font-bold text-sm">{{ substr($student->name, 0, 1) }}</span>
                    </div>
                ` : ''}
            `;
            
            messageDiv.innerHTML = content;
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }
        
        // Add Bot Response
        function addBotResponse(data) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex gap-3 animate-slide-in justify-start';
            
            let booksHTML = '';
            if (data.books && data.books.length > 0) {
                booksHTML = `
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        ${data.books.map(book => `
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-3 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <img src="${book.cover}" alt="${book.title}" class="w-full h-40 object-cover rounded-lg mb-3" onerror="this.src='https://via.placeholder.com/200x280/e5e7eb/9ca3af?text=No+Cover'">
                                <h4 class="font-semibold text-sm text-gray-900 dark:text-gray-100 line-clamp-2 mb-1">${book.title}</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">${book.author}</p>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">${book.year}</span>
                                    <span class="px-2 py-1 rounded-lg font-medium ${
                                        book.stock > 5 ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' :
                                        book.stock > 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300' :
                                        'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'
                                    }">
                                        ${book.stock > 0 ? `Stok: ${book.stock}` : 'Habis'}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span>📚 ${book.category}</span>
                                    <span>•</span>
                                    <span>🗄️ ${book.shelf_code}</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
            }
            
            let aiHTML = '';
            if (data.ai_explanation) {
                aiHTML = `
                    <div class="bg-primary-50 dark:bg-primary-900/20 border-l-4 border-primary-500 rounded-lg p-3 mt-3">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-rounded text-primary-600 dark:text-primary-400 text-lg">lightbulb</span>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1">Informasi AI</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">${data.ai_explanation}</p>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            const content = `
                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                    <span class="text-white text-lg">🤖</span>
                </div>
                
                <div class="max-w-[75%] sm:max-w-[85%]">
                    <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-3 shadow-lg">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap">${data.reply}</p>
                        ${aiHTML}
                        ${booksHTML}
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 ml-2">
                        ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                    </p>
                </div>
            `;
            
            messageDiv.innerHTML = content;
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }
        
        // Typing Indicator
        function showTypingIndicator() {
            const id = 'typing-' + Date.now();
            const typingDiv = document.createElement('div');
            typingDiv.id = id;
            typingDiv.className = 'flex gap-3 animate-slide-in justify-start';
            
            typingDiv.innerHTML = `
                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                    <span class="text-white text-lg">🤖</span>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-3 shadow-lg">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-primary-500 rounded-full animate-bounce-slow"></span>
                        <span class="w-2 h-2 bg-primary-500 rounded-full animate-bounce-slow" style="animation-delay: 0.2s;"></span>
                        <span class="w-2 h-2 bg-primary-500 rounded-full animate-bounce-slow" style="animation-delay: 0.4s;"></span>
                    </div>
                </div>
            `;
            
            messagesContainer.appendChild(typingDiv);
            scrollToBottom();
            
            return id;
        }
        
        function removeTypingIndicator(id) {
            const element = document.getElementById(id);
            if (element) {
                element.remove();
            }
        }
        
        // Save to History
        function saveToHistory(userMessage, botResponse) {
            const session = {
                id: currentSessionId,
                userMessage: userMessage,
                botResponse: botResponse.reply,
                timestamp: Date.now(),
                books: botResponse.books || []
            };
            
            chatHistory.unshift(session);
            
            // Limit to 50 recent chats
            if (chatHistory.length > 50) {
                chatHistory = chatHistory.slice(0, 50);
            }
            
            localStorage.setItem('chatHistory', JSON.stringify(chatHistory));
            updateHistoryList();
        }
        
        // Update History List
        function updateHistoryList() {
            if (chatHistory.length === 0) {
                historyList.innerHTML = `
                    <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                        <span class="material-symbols-rounded text-5xl opacity-30">history</span>
                        <p class="mt-2 text-sm">Belum ada riwayat chat</p>
                    </div>
                `;
                return;
            }
            
            historyList.innerHTML = chatHistory.map((session, index) => `
                <div class="bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-xl p-3 cursor-pointer transition-colors border border-gray-200 dark:border-gray-600" onclick="loadSession(${index})">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-rounded text-primary-600 dark:text-primary-400 text-sm">chat</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">${session.userMessage}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                ${new Date(session.timestamp).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })}
                            </p>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        // Load Session
        function loadSession(index) {
            const session = chatHistory[index];
            if (!session) return;
            
            welcomeSection.style.display = 'none';
            messagesContainer.innerHTML = '';
            
            addMessage(session.userMessage, 'user');
            addBotResponse({
                reply: session.botResponse,
                books: session.books
            });
            
            toggleSidebar();
        }
        
        // Load Chat History
        function loadChatHistory() {
            const saved = localStorage.getItem('chatHistory');
            if (saved) {
                try {
                    chatHistory = JSON.parse(saved);
                    updateHistoryList();
                } catch (e) {
                    console.error('Failed to load history:', e);
                    chatHistory = [];
                }
            }
        }
        
        // Clear History
        function clearHistory() {
            if (confirm('Yakin ingin menghapus semua riwayat chat?')) {
                chatHistory = [];
                localStorage.removeItem('chatHistory');
                updateHistoryList();
            }
        }
        
        // Delete All Chats
        function deleteAllChats() {
            if (confirm('Yakin ingin menghapus semua percakapan?')) {
                messagesContainer.innerHTML = '';
                welcomeSection.style.display = 'block';
                chatHistory = [];
                localStorage.removeItem('chatHistory');
                updateHistoryList();
                currentSessionId = Date.now();
            }
        }
        
        // Scroll to Bottom
        function scrollToBottom() {
            setTimeout(() => {
                window.scrollTo({
                    top: document.documentElement.scrollHeight,
                    behavior: 'smooth'
                });
            }, 100);
        }
    </script>
</body>
</html>