// ===============================================
// FUNGSI GLOBAL (Bisa diakses dari HTML onclick)
// ===============================================

/**
 * Menampilkan atau menyembunyikan password pada input field.
 */
function togglePassword(fieldId, button) {
    const passwordField = document.getElementById(fieldId);
    const icon = button.querySelector('i');
    if (!passwordField || !icon) return;

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

/**
 * Menampilkan modal peminjaman buku dengan animasi.
 */
function showBorrowModal() {
    const borrowModal = document.getElementById('borrowModal');
    const modalContent = borrowModal ? borrowModal.querySelector('.modal-content') : null;
    if (!borrowModal) return;

    borrowModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        borrowModal.classList.remove('opacity-0');
        if (modalContent) {
            modalContent.classList.remove('scale-95', 'opacity-0');
        }
    }, 10);
}

/**
 * Menutup modal peminjaman buku dengan animasi.
 */
function closeBorrowModal() {
    const borrowModal = document.getElementById('borrowModal');
    const modalContent = borrowModal ? borrowModal.querySelector('.modal-content') : null;
    if (!borrowModal) return;

    if (modalContent) {
        modalContent.classList.add('scale-95', 'opacity-0');
    }
    borrowModal.classList.add('opacity-0');

    setTimeout(() => {
        borrowModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}

/**
 * Menampilkan modal petunjuk peminjaman.
 */
function openBorrowGuideModal() {
    const guideModal = document.getElementById('borrowGuideModal');
    const modalContent = guideModal ? guideModal.querySelector('.modal-content') : null;
    if (!guideModal) return;

    guideModal.classList.remove('hidden');
    setTimeout(() => {
        guideModal.classList.remove('opacity-0');
        if(modalContent) modalContent.classList.remove('scale-95', 'opacity-0');
    }, 10);
}

/**
 * Menutup modal petunjuk peminjaman.
 */
function closeBorrowGuideModal() {
    const guideModal = document.getElementById('borrowGuideModal');
    const modalContent = guideModal ? guideModal.querySelector('.modal-content') : null;
    if (!guideModal) return;
    
    if(modalContent) modalContent.classList.add('scale-95', 'opacity-0');
    guideModal.classList.add('opacity-0');
    setTimeout(() => guideModal.classList.add('hidden'), 300);
}


// ===============================================
// EVENT LISTENER (Berjalan setelah halaman siap)
// ===============================================
document.addEventListener("DOMContentLoaded", function () {
    
    // 1. Mobile Menu Toggle
    const mobileMenuButton = document.getElementById("mobileMenuButton");
    const mobileMenu = document.getElementById("mobileMenu");
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener("click", function () {
            mobileMenu.classList.toggle("hidden");
        });
    }

    // 2. Form Loading States
    function setupFormLoading(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener("submit", function() {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                }
            });
        }
    }
    setupFormLoading('loginForm');
    setupFormLoading('registrationForm');
    setupFormLoading('borrowForm');
    
    // 3. Modal Event Listeners
    const borrowModal = document.getElementById('borrowModal');
    if (borrowModal) {
        borrowModal.addEventListener('click', (e) => (e.target === borrowModal) && closeBorrowModal());
        document.addEventListener('keydown', (e) => (e.key === 'Escape') && closeBorrowModal());
    }

    const guideModal = document.getElementById('borrowGuideModal');
    if (guideModal) {
        guideModal.addEventListener('click', (e) => (e.target === guideModal) && closeBorrowGuideModal());
        document.addEventListener('keydown', (e) => (e.key === 'Escape') && closeBorrowGuideModal());
    }
    
    // 4. Verification Modal Logic
    const verificationModal = document.getElementById("verificationModal");
    if (verificationModal) {
        const closeModalBtn = document.getElementById("closeModalBtn");
        function hideVerificationModal() {
            verificationModal.classList.add('opacity-0');
            setTimeout(() => verificationModal.classList.add('hidden'), 300);
        }
        closeModalBtn?.addEventListener("click", hideVerificationModal);
        verificationModal.addEventListener("click", (e) => (e.target === verificationModal) && hideVerificationModal());
    }
});