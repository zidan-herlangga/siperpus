document.addEventListener('DOMContentLoaded', () => {
            // --- DOM Elements ---
            const chatsContainer = document.querySelector('.chats-container');
            const promptForm = document.querySelector('.prompt-form');
            const promptInput = document.querySelector('.prompt-input');
            const suggestionsList = document.querySelector('.suggestions');
            const suggestionItems = document.querySelectorAll('.suggestions-item');
            const themeToggleBtn = document.getElementById('theme-toggle-btn');
            const deleteChatsBtn = document.getElementById('delete-chats-btn');
            const sendPromptBtn = document.getElementById('send-prompt-btn');

            // --- State Management ---
            let chatHistory = JSON.parse(localStorage.getItem('perpusChatHistory')) || [];
            let currentSessionId = generateSessionId();
            let currentSession = {
                id: currentSessionId,
                title: 'Percakapan Baru',
                messages: [],
                timestamp: new Date().toISOString()
            };
            let isBotResponding = false;

            // --- Initialization ---
            initTheme();
            renderHistory();
            attachEventListeners();
            focusPromptInput();

            // --- Event Listeners ---
            function attachEventListeners() {
                promptForm.addEventListener('submit', sendMessage);
                promptInput.addEventListener('input', handleInput);
                themeToggleBtn.addEventListener('click', toggleTheme);
                deleteChatsBtn.addEventListener('click', clearAllHistory);
                
                suggestionItems.forEach(item => {
                    item.addEventListener('click', () => {
                        const text = item.querySelector('.text').textContent;
                        promptInput.value = text;
                        promptInput.focus();
                        promptForm.dispatchEvent(new Event('submit'));
                    });
                });

                // Keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    if (e.ctrlKey && e.key === '/') {
                        e.preventDefault();
                        promptInput.focus();
                    }
                    
                    if (e.key === 'Escape' && document.activeElement === promptInput) {
                        promptInput.blur();
                    }
                });
            }

            // --- Core Functions ---
            function generateSessionId() {
                return Date.now().toString(36) + Math.random().toString(36).substr(2);
            }

            function focusPromptInput() {
                setTimeout(() => {
                    promptInput.focus();
                }, 500);
            }

            function handleInput() {
                // Auto-resize textarea (if you change to textarea)
                promptInput.style.height = 'auto';
                promptInput.style.height = Math.min(promptInput.scrollHeight, 120) + 'px';
            }

            function initTheme() {
                const isLightTheme = localStorage.getItem('lightTheme') === 'true';
                if (isLightTheme) {
                    document.body.classList.add('light-theme');
                    themeToggleBtn.textContent = 'dark_mode';
                }
            }

            function toggleTheme() {
                document.body.classList.toggle('light-theme');
                const isLightTheme = document.body.classList.contains('light-theme');
                localStorage.setItem('lightTheme', isLightTheme);
                themeToggleBtn.textContent = isLightTheme ? 'dark_mode' : 'light_mode';
                themeToggleBtn.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    themeToggleBtn.style.transform = 'rotate(0)';
                }, 300);
            }

            function clearAllHistory() {
                if (confirm('Apakah Anda yakin ingin menghapus semua riwayat percakapan?')) {
                    chatHistory = [];
                    localStorage.removeItem('perpusChatHistory');
                    currentSession.messages = [];
                    renderChatMessages();
                    document.body.classList.remove('chats-active');
                    
                    // Show success feedback
                    deleteChatsBtn.textContent = 'done';
                    setTimeout(() => {
                        deleteChatsBtn.textContent = 'delete';
                    }, 1000);
                }
            }

            function saveCurrentSession() {
                if (currentSession.messages.length === 0) return;

                const firstUserMessage = currentSession.messages.find(msg => msg.role === 'user' && msg.text);
                if (firstUserMessage && firstUserMessage.text) {
                    currentSession.title = firstUserMessage.text.length > 30 
                        ? firstUserMessage.text.substring(0, 30) + '...' 
                        : firstUserMessage.text;
                }

                const existingIndex = chatHistory.findIndex(s => s.id === currentSessionId);
                if (existingIndex >= 0) {
                    chatHistory[existingIndex] = currentSession;
                } else {
                    chatHistory.push(currentSession);
                }

                if (chatHistory.length > 20) chatHistory = chatHistory.slice(-20);

                localStorage.setItem('perpusChatHistory', JSON.stringify(chatHistory));
                renderHistory();
            }

            function renderHistory() {
                // This UI doesn't have a visible history list, so this function can be simplified
                // or used to manage sessions internally if needed.
                // For now, we'll just ensure the current session is saved.
            }

            function renderChatMessages() {
                chatsContainer.innerHTML = '';
                currentSession.messages.forEach(msg => {
                    if (msg.type === 'message') {
                        appendMessage(msg.role, msg.text, false);
                    } else if (msg.type === 'book') {
                        appendBookCard(msg.book, false);
                    }
                });
                scrollToBottom();
            }

            function appendMessage(role, text, save = true) {
                document.body.classList.add('chats-active');
                
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', `${role}-message`);
                
                const avatarSvg = role === 'bot' ? 'smart_toy' : 'person';
                
                messageDiv.innerHTML = `
                    <div class="avatar material-symbols-rounded">${avatarSvg}</div>
                    <div class="message-text">${text}</div>
                `;
                
                chatsContainer.appendChild(messageDiv);
                scrollToBottom();

                if (save) {
                    currentSession.messages.push({ type: 'message', role, text });
                }
            }

            function appendBookCard(book, save = true) {
                const bookCardDiv = document.createElement('div');
                bookCardDiv.classList.add('message', 'bot-message');
                
                bookCardDiv.innerHTML = `
                    <div class="avatar material-symbols-rounded">smart_toy</div>
                    <div class="book-card">
                        <img src="${book.cover}" class="book-cover" onerror="this.src='https://via.placeholder.com/70x95/f0f0f0/cccccc?text=No+Cover'" alt="Cover ${book.title}">
                        <div class="book-details">
                            <h4>${book.title}</h4>
                            <p>oleh ${book.author} (${book.year})</p>
                            <div class="book-meta">
                                <span class="meta-tag">${book.category}</span>
                                <span class="meta-tag">Rak: ${book.shelf_code}</span>
                                <span class="meta-tag ${book.stock > 0 ? 'in-stock' : 'out-of-stock'}">Stok: ${book.stock}</span>
                            </div>
                        </div>
                    </div>
                `;
                chatsContainer.appendChild(bookCardDiv);
                scrollToBottom();

                if (save) {
                    currentSession.messages.push({ type: 'book', book });
                }
            }

            function showTypingIndicator() {
                const typingDiv = document.createElement('div');
                typingDiv.classList.add('message', 'bot-message', 'loading');
                typingDiv.innerHTML = `
                    <div class="avatar material-symbols-rounded">smart_toy</div>
                    <div class="loading-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                `;
                chatsContainer.appendChild(typingDiv);
                scrollToBottom();
            }

            function hideTypingIndicator() {
                const typingIndicator = chatsContainer.querySelector('.loading');
                if (typingIndicator) {
                    typingIndicator.remove();
                }
            }

            function scrollToBottom() {
                setTimeout(() => {
                    chatsContainer.scrollTop = chatsContainer.scrollHeight;
                }, 100);
            }

            async function sendMessage(event) {
                event.preventDefault();
                const message = promptInput.value.trim();
                if (!message) return;

                appendMessage('user', message);
                promptInput.value = '';
                promptInput.style.height = 'auto';
                
                showTypingIndicator();
                isBotResponding = true;

                try {
                    const res = await fetch("{{ route('chat.send') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ message })
                    });
                    
                    const data = await res.json();
                    hideTypingIndicator();
                    isBotResponding = false;
                    
                    // Urutan yang benar: pesan utama -> penjelasan AI -> book cards
                    if (data.reply) {
                        appendMessage('bot', data.reply);
                    }
                    
                    if (data.ai_explanation) {
                        appendMessage('bot', data.ai_explanation);
                    }
                    
                    if (data.books && data.books.length > 0) {
                        data.books.forEach(book => appendBookCard(book));
                    }
                    
                    saveCurrentSession();

                } catch (error) {
                    hideTypingIndicator();
                    appendMessage('bot', "Maaf, terjadi kesalahan koneksi. Silakan coba lagi.");
                    console.error("Error:", error);
                    isBotResponding = false;
                }
                
                // Return focus to input
                setTimeout(() => {
                    promptInput.focus();
                }, 100);
            }
        });