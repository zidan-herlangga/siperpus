document.addEventListener('DOMContentLoaded', function () {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    const form = document.querySelector('#borrowForm');
    let stockCheckInterval;

    // Function to update stock display
    function updateStockDisplay(stock) {
        const stockElement = document.querySelector('.text-3xl.font-bold.text-green-600');
        const stockBar = document.querySelector('.bg-green-600.h-2.rounded-full');
        const stockStatus = document.querySelector('.text-xs.text-gray-500.mt-1');
        const availabilityBadge = document.querySelector('.bg-green-500.text-white.rounded-full.w-10.h-10');
        const availabilityText = document.querySelector('.bg-white\\/20.backdrop-blur.text-white.px-3.py-1.rounded-full.text-sm.font-medium.flex.items-center.border.border-white\\/30');
        const borrowButton = document.querySelector('button[onclick="showBorrowModal()"]');

        if (stockElement) {
            stockElement.textContent = stock;
        }

        if (stockBar) {
            stockBar.style.width = `${Math.min(100, stock * 10)}%`;
        }

        if (stockStatus) {
            stockStatus.textContent = stock > 5 ? 'Tersedia' : 'Terbatas';
        }

        if (availabilityBadge) {
            if (stock > 0) {
                availabilityBadge.classList.remove('bg-red-500');
                availabilityBadge.classList.add('bg-green-500');
                availabilityBadge.innerHTML = '<i class="fas fa-check"></i>';
            } else {
                availabilityBadge.classList.remove('bg-green-500');
                availabilityBadge.classList.add('bg-red-500');
                availabilityBadge.innerHTML = '<i class="fas fa-times"></i>';
            }
        }

        if (availabilityText) {
            if (stock > 0) {
                availabilityText.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Tersedia';
            } else {
                availabilityText.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Stok Habis';
            }
        }

        if (borrowButton) {
            if (stock > 0) {
                borrowButton.disabled = false;
                borrowButton.classList.remove('bg-gray-400');
                borrowButton.classList.add('bg-gradient-to-r', 'from-green-600', 'to-emerald-600');
            } else {
                borrowButton.disabled = true;
                borrowButton.classList.remove('bg-gradient-to-r', 'from-green-600', 'to-emerald-600');
                borrowButton.classList.add('bg-gray-400');
            }
        }
    }

    // Function to check stock
    if (!form || !form.hasAttribute('action')) return;
    const bookId = new URL(form.action).pathname.split('/').pop()

    function checkStock() {
        fetch(`/books/${bookId}/stock`)
            .then(response => response.json())
            .then(data => {
                updateStockDisplay(data.stock);
            })
            .catch(error => console.error('Error checking stock:', error));
    }

    // Start checking stock every 5 seconds
    stockCheckInterval = setInterval(checkStock, 5000);

    // Clear interval when page is unloaded
    window.addEventListener('beforeunload', function () {
        clearInterval(stockCheckInterval);
    });

    // Tab functionality
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const tabId = this.getAttribute('data-tab');
            tabBtns.forEach(tab => {
                tab.classList.remove('border-green-500', 'text-green-600');
                tab.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-green-500', 'text-green-600');
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
        borrowForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (errorMessageDiv && errorMessageDiv.parentNode) {
                errorMessageDiv.parentNode.removeChild(errorMessageDiv);
                errorMessageDiv = null;
            }
            loadingState.classList.remove('hidden');
            closeBorrowModal();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
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
                    setTimeout(() => {
                        successNotification.classList.add('hidden');
                    }, 3000);

                    // Check stock immediately after successful borrowing
                    checkStock();
                })
                .catch(error => { /* Error handled */ });
        });
    }
});

function showBorrowModal() {
    const modal = document.getElementById('borrowModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        modal.querySelector('.modal-content').classList.remove('scale-95');
        modal.querySelector('.modal-content').classList.add('scale-100');
    }, 10);
}

function closeBorrowModal() {
    const modal = document.getElementById('borrowModal');
    modal.querySelector('.modal-content').classList.remove('scale-100');
    modal.querySelector('.modal-content').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 200);
}

document.getElementById('borrowModal').addEventListener('click', function (e) {
    if (e.target === this) {
        closeBorrowModal();
    }
});