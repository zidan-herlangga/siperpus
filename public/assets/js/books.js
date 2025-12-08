document.addEventListener('DOMContentLoaded', function () {
    // Animasi scroll untuk elemen
    const animateOnScroll = function () {
        const elements = document.querySelectorAll('.animate-on-scroll');

        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementBottom = element.getBoundingClientRect().bottom;

            if (elementTop < window.innerHeight && elementBottom > 0) {
                element.classList.add('animated');
            }
        });
    };

    // Jalankan sekali saat halaman dimuat
    animateOnScroll();

    // Jalankan saat scroll
    window.addEventListener('scroll', animateOnScroll);

    // Tampilkan loading indicator saat form dikirim
    const searchForm = document.querySelector("form[action='{{ route('books.index') }}']");
    if (searchForm) {
        searchForm.addEventListener('submit', function () {
            document.getElementById('loadingIndicator').classList.remove('hidden');
        });
    }
});

function openBorrowGuideModal() {
    const modal = document.getElementById('borrowGuideModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Animasi modal
    setTimeout(() => {
        modal.querySelector('.modal-content').classList.remove('scale-95');
        modal.querySelector('.modal-content').classList.add('scale-100');
    }, 10);
}

function closeBorrowGuideModal() {
    const modal = document.getElementById('borrowGuideModal');

    // Animasi modal
    modal.querySelector('.modal-content').classList.remove('scale-100');
    modal.querySelector('.modal-content').classList.add('scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 200);
}

// Tutup modal saat klik di luar konten modal
document.getElementById('borrowGuideModal').addEventListener('click', function (e) {
    if (e.target === this) {
        closeBorrowGuideModal();
    }
});