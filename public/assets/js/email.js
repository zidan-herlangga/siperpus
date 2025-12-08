document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const emailField = document.getElementById('email');
        
        emailField.addEventListener('blur', function() {
            validateEmail(this);
        });
        
        emailField.addEventListener('input', function() {
            if (this.classList.contains('border-red-500') || this.classList.contains('border-green-500')) {
                validateEmail(this);
            }
        });
        
        function validateEmail(field) {
            const value = field.value.trim();
            const validationMessage = field.parentElement.querySelector('.validation-message');
            
            if (value === '') {
                field.classList.remove('border-green-500');
                field.classList.add('border-red-500');
                validationMessage.classList.remove('success');
                validationMessage.classList.add('error');
                validationMessage.textContent = 'Email wajib diisi';
                return false;
            } else if (!value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                field.classList.remove('border-green-500');
                field.classList.add('border-red-500');
                validationMessage.classList.remove('success');
                validationMessage.classList.add('error');
                validationMessage.textContent = 'Format email tidak valid';
                return false;
            } else {
                field.classList.remove('border-red-500');
                field.classList.add('border-green-500');
                validationMessage.classList.remove('error');
                validationMessage.classList.add('success');
                validationMessage.textContent = '✓ Email valid';
                return true;
            }
        }
        
        // Form submission with loading state
        const forgotPasswordForm = document.getElementById('forgotPasswordForm');
        if (forgotPasswordForm) {
            forgotPasswordForm.addEventListener('submit', function(e) {
                // Validate email
                if (!validateEmail(emailField)) {
                    e.preventDefault();
                    return;
                }
                
                // Show loading state
                const submitButton = document.getElementById('submitButton');
                const buttonText = document.getElementById('buttonText');
                const buttonLoader = document.getElementById('buttonLoader');
                
                submitButton.disabled = true;
                buttonText.textContent = 'Mengirim...';
                buttonLoader.classList.remove('hidden');
            });
        }
    });