@extends('layouts.app')

@section('title', 'Chatbot Perpustakaan')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    <div id="sidebar" class="fixed md:relative h-full bg-white shadow-2xl transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-30 w-80 flex flex-col">
        <!-- Sidebar Header -->
        <div class="px-5 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-lg">Riwayat Chat</h2>
                        <p class="text-xs opacity-80">Kelola percakapan Anda</p>
                    </div>
                </div>
                <button id="close-sidebar" class="md:hidden text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Search Bar -->
            <div class="relative">
                <input id="search-history" type="text" placeholder="Cari percakapan..." 
                    class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 pl-10 text-sm placeholder-white/60 focus:outline-none focus:bg-white/20 focus:border-white/40 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        
        <!-- History Content -->
        <div class="flex-1 overflow-hidden flex flex-col">
            <!-- Filter Tabs -->
            <div class="px-5 py-3 border-b bg-gray-50">
                <div class="flex space-x-1">
                    <button class="filter-tab active flex-1 px-3 py-1.5 text-xs font-medium rounded-md bg-white text-green-600 shadow-sm" data-filter="all">
                        Semua
                    </button>
                    <button class="filter-tab flex-1 px-3 py-1.5 text-xs font-medium rounded-md text-gray-600 hover:bg-gray-100" data-filter="today">
                        Hari Ini
                    </button>
                    <button class="filter-tab flex-1 px-3 py-1.5 text-xs font-medium rounded-md text-gray-600 hover:bg-gray-100" data-filter="week">
                        Minggu Ini
                    </button>
                </div>
            </div>
            
            <!-- History List -->
            <div class="flex-1 overflow-y-auto p-4">
                <div id="history-list" class="space-y-2">
                    <!-- Chat history will be added here dynamically -->
                </div>
                
                <!-- Empty State -->
                <div id="empty-history" class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium">Belum ada percakapan</p>
                    <p class="text-xs mt-1">Mulai percakapan baru dengan chatbot</p>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Footer -->
        <div class="p-4 border-t bg-gray-50">
            <div class="space-y-2">
                <button id="clear-history" class="w-full bg-red-50 hover:bg-red-100 text-red-600 py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Hapus Semua Riwayat
                </button>
                <button id="new-chat" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Percakapan Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-20 hidden md:hidden"></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header with menu button -->
        <div class="px-4 py-3 border-b bg-white shadow-sm">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <button id="menu-toggle" class="mr-3 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="font-semibold text-gray-900">Chatbot Perpustakaan</h1>
                            <div class="flex items-center space-x-2">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                <span class="text-xs text-gray-500">Online</span>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Area -->
        <div id="chat-area" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Selamat datang di Chatbot Perpustakaan</h2>
                <p class="text-gray-600">Saya siap membantu Anda menemukan informasi buku</p>
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Cari buku</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Info kategori</span>
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Lokasi rak</span>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 border-t bg-white">
            <div class="flex items-end gap-2 max-w-4xl mx-auto">
                <button class="text-gray-500 hover:text-gray-700 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>
                <div class="flex-1 relative">
                    <input id="chat-input" type="text" placeholder="Ketik pesan Anda..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-12 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        onkeypress="if(event.key === 'Enter') sendMessage()">
                    <button class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
                <button onclick="sendMessage()"
                    class="bg-green-600 hover:bg-green-700 text-white p-2.5 rounded-lg transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // DOM Elements
    const chatArea = document.getElementById('chat-area');
    const chatInput = document.getElementById('chat-input');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const menuToggle = document.getElementById('menu-toggle');
    const closeSidebar = document.getElementById('close-sidebar');
    const clearHistoryBtn = document.getElementById('clear-history');
    const newChatBtn = document.getElementById('new-chat');
    const historyList = document.getElementById('history-list');
    const emptyHistory = document.getElementById('empty-history');
    const searchInput = document.getElementById('search-history');
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    // Chat history management
    let chatHistory = JSON.parse(localStorage.getItem('chatHistory')) || [];
    let currentSessionId = generateSessionId();
    let currentSession = {
        id: currentSessionId,
        title: 'Percakapan Baru',
        messages: [],
        timestamp: new Date().toISOString()
    };
    let currentFilter = 'all';
    
    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        renderHistory();
        updateEmptyHistoryVisibility();
        
        // Setup filter tabs
        filterTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                filterTabs.forEach(t => {
                    t.classList.remove('active', 'bg-white', 'text-green-600', 'shadow-sm');
                    t.classList.add('text-gray-600');
                });
                tab.classList.add('active', 'bg-white', 'text-green-600', 'shadow-sm');
                tab.classList.remove('text-gray-600');
                currentFilter = tab.dataset.filter;
                renderHistory();
            });
        });
        
        // Setup search
        searchInput.addEventListener('input', (e) => {
            renderHistory(e.target.value);
        });
    });
    
    // Generate unique session ID
    function generateSessionId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }
    
    // Toggle sidebar
    menuToggle.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    });
    
    closeSidebar.addEventListener('click', closeSidebarFunc);
    overlay.addEventListener('click', closeSidebarFunc);
    
    function closeSidebarFunc() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
    
    // New chat
    newChatBtn.addEventListener('click', () => {
        saveCurrentSession();
        currentSessionId = generateSessionId();
        currentSession = {
            id: currentSessionId,
            title: 'Percakapan Baru',
            messages: [],
            timestamp: new Date().toISOString()
        };
        chatArea.innerHTML = `
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Selamat datang di Chatbot Perpustakaan</h2>
                <p class="text-gray-600">Saya siap membantu Anda menemukan informasi buku</p>
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Cari buku</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Info kategori</span>
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Lokasi rak</span>
                </div>
            </div>
        `;
        closeSidebarFunc();
    });
    
    // Clear history
    clearHistoryBtn.addEventListener('click', () => {
        if (confirm('Apakah Anda yakin ingin menghapus semua riwayat percakapan? Tindakan ini tidak dapat dibatalkan.')) {
            chatHistory = [];
            localStorage.removeItem('chatHistory');
            renderHistory();
            updateEmptyHistoryVisibility();
        }
    });
    
    // Filter history by date
    function filterHistoryByDate(session) {
        const sessionDate = new Date(session.timestamp);
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const weekAgo = new Date(today);
        weekAgo.setDate(weekAgo.getDate() - 7);
        
        switch(currentFilter) {
            case 'today':
                return sessionDate >= today;
            case 'week':
                return sessionDate >= weekAgo;
            default:
                return true;
        }
    }
    
    // Render history
    function renderHistory(searchTerm = '') {
        historyList.innerHTML = '';
        
        let filteredHistory = chatHistory.filter(filterHistoryByDate);
        
        if (searchTerm) {
            filteredHistory = filteredHistory.filter(session => 
                session.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                session.messages.some(msg => 
                    msg.type === 'message' && 
                    msg.text && 
                    msg.text.toLowerCase().includes(searchTerm.toLowerCase())
                )
            );
        }
        
        if (filteredHistory.length === 0) {
            historyList.innerHTML = `
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="text-sm text-gray-500">${searchTerm ? 'Tidak ada hasil pencarian' : 'Tidak ada percakapan'}</p>
                </div>
            `;
            return;
        }
        
        filteredHistory.forEach(session => {
            const historyItem = document.createElement('div');
            historyItem.className = 'group p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-gray-200';
            
            const date = new Date(session.timestamp);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            let timeLabel;
            if (diffDays === 0) {
                timeLabel = 'Hari ini';
            } else if (diffDays === 1) {
                timeLabel = 'Kemarin';
            } else if (diffDays < 7) {
                timeLabel = `${diffDays} hari lalu`;
            } else {
                timeLabel = date.toLocaleDateString('id-ID', { 
                    day: 'numeric', 
                    month: 'short', 
                    year: 'numeric' 
                });
            }
            
            const messageCount = session.messages.filter(m => m.type === 'message' && m.role === 'user').length;
            
            // --- PERBAIKAN: Pemeriksaan aman untuk pesan terakhir ---
            let lastMessagePreview = '';
            if (session.messages.length > 0) {
                // Cari pesan terakhir yang memiliki teks
                const lastTextMessage = session.messages.slice().reverse().find(msg => msg.type === 'message' && msg.text);
                if (lastTextMessage && lastTextMessage.text) {
                    lastMessagePreview = `<p class="text-xs text-gray-400 mt-1 truncate">${lastTextMessage.text.substring(0, 50)}...</p>`;
                }
            }
            
            historyItem.innerHTML = `
                <div class="flex justify-between items-start">
                    <div class="flex-1 min-w-0 pr-2">
                        <h3 class="font-medium text-gray-900 truncate text-sm">${session.title}</h3>
                        <div class="flex items-center mt-1 space-x-2">
                            <span class="text-xs text-gray-500">${timeLabel}</span>
                            <span class="text-xs text-gray-400">•</span>
                            <span class="text-xs text-gray-500">${messageCount} pesan</span>
                        </div>
                        ${lastMessagePreview}
                    </div>
                    <button class="delete-session opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 ml-2 transition-all duration-200" data-id="${session.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            `;
            
            historyItem.addEventListener('click', (e) => {
                if (!e.target.closest('.delete-session')) {
                    loadSession(session.id);
                    closeSidebarFunc();
                }
            });
            
            const deleteBtn = historyItem.querySelector('.delete-session');
            deleteBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                deleteSession(session.id);
            });
            
            historyList.appendChild(historyItem);
        });
    }
    
    // Update empty history visibility
    function updateEmptyHistoryVisibility() {
        if (chatHistory.length === 0) {
            emptyHistory.style.display = 'flex';
        } else {
            emptyHistory.style.display = 'none';
        }
    }
    
    // Delete a specific session
    function deleteSession(sessionId) {
        if (confirm('Hapus percakapan ini?')) {
            chatHistory = chatHistory.filter(session => session.id !== sessionId);
            localStorage.setItem('chatHistory', JSON.stringify(chatHistory));
            renderHistory();
            updateEmptyHistoryVisibility();
        }
    }
    
    // Load a session
    function loadSession(sessionId) {
        const session = chatHistory.find(s => s.id === sessionId);
        if (session) {
            currentSession = session;
            currentSessionId = sessionId;
            renderChatMessages();
        }
    }
    
    // Render chat messages
    function renderChatMessages() {
        chatArea.innerHTML = '';
        
        currentSession.messages.forEach(msg => {
            if (msg.type === 'message') {
                appendMessage(msg.role, msg.text);
            } else if (msg.type === 'book') {
                appendBookCard(msg.book);
            }
        });
    }
    
    // Save current session
    function saveCurrentSession() {
        if (currentSession.messages.length === 0) return;
        
        // --- PERBAIKAN: Pemeriksaan aman untuk pesan pertama ---
        // Generate title from first user message
        if (currentSession.messages.length > 0 && currentSession.title === 'Percakapan Baru') {
            const firstUserMessage = currentSession.messages.find(msg => msg.role === 'user' && msg.text);
            if (firstUserMessage && firstUserMessage.text) {
                currentSession.title = firstUserMessage.text.length > 30 
                    ? firstUserMessage.text.substring(0, 30) + '...' 
                    : firstUserMessage.text;
            }
        }
        
        // Update or add session
        const existingIndex = chatHistory.findIndex(s => s.id === currentSessionId);
        if (existingIndex >= 0) {
            chatHistory[existingIndex] = currentSession;
        } else {
            chatHistory.unshift(currentSession);
        }
        
        // Keep only last 20 sessions
        if (chatHistory.length > 20) {
            chatHistory = chatHistory.slice(0, 20);
        }
        
        localStorage.setItem('chatHistory', JSON.stringify(chatHistory));
        renderHistory();
        updateEmptyHistoryVisibility();
    }
    
    // Append message to chat
    function appendMessage(role, text, isLoading = false) {
        const bubble = document.createElement('div');
        
        bubble.className =
            "max-w-[75%] px-4 py-2.5 rounded-2xl shadow-sm text-sm leading-relaxed " +
            (role === 'user'
                ? "bg-gradient-to-r from-green-600 to-green-700 text-white ml-auto"
                : "bg-white text-gray-800 mr-auto border border-gray-200");
        
        if (isLoading) {
            bubble.innerHTML = `
                <div class='flex gap-1.5 items-center loading-dots'>
                    <span class='dot'></span>
                    <span class='dot'></span>
                    <span class='dot'></span>
                </div>
            `;
            bubble.id = 'typing-indicator';
        } else {
            bubble.innerHTML = text;
            
            // Save to current session
            if (!isLoading) {
                currentSession.messages.push({
                    type: 'message',
                    role: role,
                    text: text
                });
            }
        }
        
        chatArea.appendChild(bubble);
        chatArea.scrollTop = chatArea.scrollHeight;
    }
    
    // Append book card
    function appendBookCard(book) {
        const card = document.createElement('div');
        card.className = "bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex gap-4 max-w-[90%] mr-auto hover:shadow-md transition-shadow duration-200";
        
        card.innerHTML = `
            <img src="${book.cover}" class="w-20 h-28 object-cover rounded-lg shadow-sm">
            <div class="flex-1">
                <div class="font-semibold text-gray-900 mb-1">${book.title}</div>
                <div class="text-sm text-gray-600 mb-2">✍️ ${book.author}</div>
                <div class="flex flex-wrap gap-2 mb-2">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium">${book.category}</span>
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">Rak: ${book.shelf_code}</span>
                </div>
                <p class="text-sm text-gray-700 line-clamp-2">${book.detail}</p>
            </div>
        `;
        
        chatArea.appendChild(card);
        chatArea.scrollTop = chatArea.scrollHeight;
        
        // Save to current session
        currentSession.messages.push({
            type: 'book',
            book: book
        });
    }
    
    // Send message
    async function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;
        
        appendMessage('user', message);
        chatInput.value = '';
        
        appendMessage('bot', '', true);
        
        try {
            const res = await fetch("{{ route('chat.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content
                },
                body: JSON.stringify({ message })
            });
            
            const data = await res.json();
            document.getElementById('typing-indicator')?.remove();
            
            appendMessage('bot', data.reply ?? "Tidak ada jawaban.");
            
            if (data.books && data.books.length > 0) {
                data.books.forEach(book => appendBookCard(book));
            }
            
            // Save the session after getting the response
            saveCurrentSession();
            
        } catch (error) {
            document.getElementById('typing-indicator')?.remove();
            appendMessage('bot', "Kesalahan: " + error.message);
        }
    }
</script>

<style>
    .loading-dots .dot {
        width: 6px;
        height: 6px;
        background: #10b981;
        border-radius: 50%;
        display: inline-block;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .loading-dots .dot:nth-child(1) { animation-delay: -0.32s; }
    .loading-dots .dot:nth-child(2) { animation-delay: -0.16s; }
    
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection