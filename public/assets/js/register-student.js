<script>
        // Toggle password visibility
        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');

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

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = '';
            
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]+/)) strength += 25;
            if (password.match(/[A-Z]+/)) strength += 25;
            if (password.match(/[0-9]+/)) strength += 25;
            
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            strengthBar.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthBar.className = 'h-1.5 rounded-full transition-all duration-300 bg-red-500';
                feedback = 'Lemah';
            } else if (strength <= 50) {
                strengthBar.className = 'h-1.5 rounded-full transition-all duration-300 bg-orange-500';
                feedback = 'Sedang';
            } else if (strength <= 75) {
                strengthBar.className = 'h-1.5 rounded-full transition-all duration-300 bg-yellow-500';
                feedback = 'Kuat';
            } else {
                strengthBar.className = 'h-1.5 rounded-full transition-all duration-300 bg-green-500';
                feedback = 'Sangat Kuat';
            }
            
            strengthText.textContent = feedback;
            strengthText.className = 'text-xs font-medium ' + 
                (strength <= 25 ? 'text-red-500' : 
                 strength <= 50 ? 'text-orange-500' : 
                 strength <= 75 ? 'text-yellow-500' : 'text-green-500');
            
            return strength;
        }

        // Form validation
        function validateField(field) {
            const value = field.value.trim();
            const fieldName = field.name;
            const validationMessage = field.parentElement.querySelector('.validation-message');
            
            let isValid = true;
            let message = '';
            
            if (value === '') {
                isValid = false;
                message = 'Field ini wajib diisi';
            } else if (fieldName === 'email' && !value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                isValid = false;
                message = 'Format email tidak valid';
            } else if (fieldName === 'password' && value.length < 8) {
                isValid = false;
                message = 'Password minimal 8 karakter';
            } else if (fieldName === 'password_confirmation') {
                const password = document.getElementById('password').value;
                if (value !== password) {
                    isValid = false;
                    message = 'Password tidak cocok';
                }
            }
            
            if (isValid) {
                field.classList.remove('border-red-500');
                field.classList.add('border-green-500');
                validationMessage.classList.remove('error');
                validationMessage.classList.add('success');
                validationMessage.textContent = '✓ Valid';
            } else {
                field.classList.remove('border-green-500');
                field.classList.add('border-red-500');
                validationMessage.classList.remove('success');
                validationMessage.classList.add('error');
                validationMessage.textContent = message;
            }
            
            return isValid;
        }

        // Update progress steps based on form sections
        function updateProgressSteps() {
            const formSections = document.querySelectorAll('.form-section');
            const stepIndicators = document.querySelectorAll('.step-indicator');
            
            formSections.forEach((section, index) => {
                const inputs = section.querySelectorAll('input, select');
                let sectionValid = true;
                
                inputs.forEach(input => {
                    if (input.hasAttribute('required') && input.value.trim() === '') {
                        sectionValid = false;
                    }
                });
                
                if (sectionValid) {
                    stepIndicators[index].classList.add('active');
                } else {
                    stepIndicators[index].classList.remove('active');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Password strength checker
            const passwordField = document.getElementById('password');
            if (passwordField) {
                passwordField.addEventListener('input', function() {
                    checkPasswordStrength(this.value);
                });
            }
            
            // Form validation on input
            const formFields = document.querySelectorAll('input, select');
            formFields.forEach(field => {
                field.addEventListener('blur', function() {
                    validateField(this);
                    updateProgressSteps();
                });
                
                field.addEventListener('input', function() {
                    if (this.classList.contains('border-red-500') || this.classList.contains('border-green-500')) {
                        validateField(this);
                        updateProgressSteps();
                    }
                });
            });
            
            // Form submission with loading state
            const registrationForm = document.getElementById('registrationForm');
            if (registrationForm) {
                registrationForm.addEventListener('submit', function(e) {
                    // Validate all fields
                    let formValid = true;
                    formFields.forEach(field => {
                        if (!validateField(field)) {
                            formValid = false;
                        }
                    });
                    
                    if (!formValid) {
                        e.preventDefault();
                        showNotification('Form Tidak Valid', 'Silakan periksa kembali input Anda.', 'error');
                        return;
                    }
                    
                    // Show loading state
                    const submitButton = document.getElementById('submitButton');
                    const buttonText = document.getElementById('buttonText');
                    const buttonLoader = document.getElementById('buttonLoader');
                    
                    submitButton.disabled = true;
                    buttonText.textContent = 'Mendaftarkan...';
                    buttonLoader.classList.remove('hidden');
                });
            }
            
            // Initialize progress steps
            updateProgressSteps();
        });
    </script>s