// Toggle password visibility
        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('password-icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Generate angka acak untuk pertanyaan sederhana
        function generateRandomNumberQuestion() {
            const num1 = Math.floor(Math.random() * 10) + 1; // Angka antara 1-10
            const num2 = Math.floor(Math.random() * 10) + 1; // Angka antara 1-10
            const sum = num1 + num2;

            // Tampilkan pertanyaan di label
            const questionLabel = document.getElementById('randomNumberDisplay');
            questionLabel.innerHTML = `<i class="fas fa-shield-alt mr-1 text-green-600"></i> Berapa hasil dari ${num1} + ${num2}?`;

            return sum;
        }

        // Simpan jawaban yang benar
        let correctAnswer = generateRandomNumberQuestion();

        // Show notification function
        function showNotification(title, message, type) {
            const notification = document.getElementById('notification');
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            
            // Set icon based on type
            if (type === 'error') {
                notificationIcon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500 text-xl"></i>';
            } else if (type === 'success') {
                notificationIcon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xl"></i>';
            }
            
            // Set content
            notificationTitle.textContent = title;
            notificationMessage.textContent = message;
            
            // Show notification
            notification.classList.remove('translate-x-full');
            
            // Hide after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
            }, 3000);
        }

        // Validasi jawaban sebelum submit form
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const userAnswer = parseInt(document.getElementById('randomNumberInput').value, 10);
            
            if (userAnswer !== correctAnswer) {
                event.preventDefault();
                showNotification('Jawaban Salah', 'Jawaban Anda salah. Silahkan coba lagi.', 'error');
                
                // Regenerate pertanyaan baru
                correctAnswer = generateRandomNumberQuestion();
                document.getElementById('randomNumberInput').value = '';
                
                // Focus on input
                document.getElementById('randomNumberInput').focus();
            } else {
                // Show loading state
                const submitButton = document.getElementById('submitButton');
                const buttonText = document.getElementById('buttonText');
                const buttonLoader = document.getElementById('buttonLoader');
                
                submitButton.disabled = true;
                buttonText.textContent = 'Memproses...';
                buttonLoader.classList.remove('hidden');
            }
        });

        // Add input validation feedback
        document.getElementById('login').addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.add('border-green-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-green-500');
                this.classList.add('border-gray-300');
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.add('border-green-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-green-500');
                this.classList.add('border-gray-300');
            }
        });

        document.getElementById('randomNumberInput').addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.add('border-green-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-green-500');
                this.classList.add('border-gray-300');
            }
        });