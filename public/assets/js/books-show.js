document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    tabBtns.forEach(tab => { tab.classList.remove('border-green-500', 'text-green-600'); tab.classList.add('border-transparent', 'text-gray-500'); });
                    this.classList.remove('border-transparent', 'text-gray-500'); this.classList.add('border-green-500', 'text-green-600');
                    tabContents.forEach(content => { content.classList.add('hidden'); });
                    document.getElementById(tabId).classList.remove('hidden');
                });
            });

            const borrowForm = document.getElementById('borrowForm');
            const loadingState = document.getElementById('loadingState');
            const successNotification = document.getElementById('successNotification');
            const borrowModal = document.getElementById('borrowModal');
            let errorMessageDiv = null;

            if (borrowForm) {
                borrowForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (errorMessageDiv && errorMessageDiv.parentNode) { errorMessageDiv.parentNode.removeChild(errorMessageDiv); errorMessageDiv = null; }
                    loadingState.classList.remove('hidden'); closeBorrowModal();
                    const formData = new FormData(this);
                    fetch(this.action, { method: 'POST', body: formData, headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => {
                        loadingState.classList.add('hidden');
                        if (!response.ok) {
                            return response.json().then(data => {
                                const errorMessages = Object.values(data.errors || {}).flat().join('<br>');
                                errorMessageDiv = document.createElement('div');
                                errorMessageDiv.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"><strong class="font-bold">Gagal! </strong><span class="block sm:inline">${errorMessages || 'Terjadi kesalahan.'}</span></div>`;
                                borrowForm.prepend(errorMessageDiv);
                                showBorrowModal();
                                throw new Error('Validation failed');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        successNotification.querySelector('#successMessage').innerText = data.message;
                        successNotification.classList.remove('hidden');
                        setTimeout(() => { successNotification.classList.add('hidden'); }, 3000);
                        // window.location.href = data.redirect_url;
                    })
                    .catch(error => { /* Error handled */ });
                });
            }
        });

        function showBorrowModal() {
            const modal = document.getElementById('borrowModal');
            modal.classList.remove('hidden'); document.body.style.overflow = 'hidden';
            setTimeout(() => { modal.querySelector('.modal-content').classList.remove('scale-95'); modal.querySelector('.modal-content').classList.add('scale-100'); }, 10);
        }
        function closeBorrowModal() {
            const modal = document.getElementById('borrowModal');
            modal.querySelector('.modal-content').classList.remove('scale-100'); modal.querySelector('.modal-content').classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 200);
        }
        document.getElementById('borrowModal').addEventListener('click', function(e) { if (e.target === this) { closeBorrowModal(); } });