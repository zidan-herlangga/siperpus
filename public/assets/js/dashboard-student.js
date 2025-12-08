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
        document.getElementById('borrowGuideModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBorrowGuideModal();
            }
        });