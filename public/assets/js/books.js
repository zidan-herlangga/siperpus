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
    const searchForm = document.querySelector('[data-search-url]');
    const searchUrl = searchForm ? searchForm.dataset.searchUrl : '';
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah form submit normal

            const searchInput = this.querySelector('input[name="search"]');
            const query = searchInput ? searchInput.value : '';

            // Gunakan searchUrl yang sudah kita dapatkan
            fetch(`${searchUrl}?search=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html', // Karena mungkin Anda me-render partial view
                }
            })
                .then(response => response.text())
                .then(html => {
                    // Logika untuk memperbarui daftar buku di halaman
                    // document.getElementById('book-list-container').innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
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