
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

    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = '';
        
        // Check requirements
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]+/.test(password);
        const hasLowercase = /[a-z]+/.test(password);
        const hasNumber = /[0-9]+/.test(password);
        
        // Update requirement indicators
        updateRequirement('req-length', hasLength);
        updateRequirement('req-uppercase', hasUppercase);
        updateRequirement('req-lowercase', hasLowercase);
        updateRequirement('req-number', hasNumber);
        
        // Calculate strength
        if (hasLength) strength += 25;
        if (hasUppercase) strength += 25;
        if (hasLowercase) strength += 25;
        if (hasNumber) strength += 25;
        
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
    
    // Update requirement indicator
    function updateRequirement(id, isMet) {
        const element = document.getElementById(id);
        const icon = element.querySelector('i');
        
        if (isMet) {
            icon.classList.remove('fa-times-circle', 'text-red-500');
            icon.classList.add('fa-check-circle', 'text-green-500');
        } else {
            icon.classList.remove('fa-check-circle', 'text-green-500');
            icon.classList.add('fa-times-circle', 'text-red-500');
        }
    }
    
    // Check password match
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const matchIndicator = document.getElementById('password-match');
        
        if (confirmPassword === '') {
            matchIndicator.textContent = '';
            return true;
        }
        
        if (password === confirmPassword) {
            matchIndicator.textContent = '✓ Password cocok';
            matchIndicator.className = 'text-xs mt-1 text-green-600';
            return true;
        } else {
            matchIndicator.textContent = '✗ Password tidak cocok';
            matchIndicator.className = 'text-xs mt-1 text-red-600';
            return false;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Password strength checker
        const passwordField = document.getElementById('password');
        if (passwordField) {
            passwordField.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });
        }
        
        // Password match checker
        const confirmPasswordField = document.getElementById('password_confirmation');
        if (confirmPasswordField) {
            confirmPasswordField.addEventListener('input', checkPasswordMatch);
        }
        
        // Form submission with loading state
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        if (resetPasswordForm) {
            resetPasswordForm.addEventListener('submit', function(e) {
                // Validate password match
                if (!checkPasswordMatch()) {
                    e.preventDefault();
                    return;
                }
                
                // Show loading state
                const submitButton = document.getElementById('submitButton');
                const buttonText = document.getElementById('buttonText');
                const buttonLoader = document.getElementById('buttonLoader');
                
                submitButton.disabled = true;
                buttonText.textContent = 'Mereset...';
                buttonLoader.classList.remove('hidden');
            });
        }
    });
