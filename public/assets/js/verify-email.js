
document.addEventListener('DOMContentLoaded', function () {
    // Form submission with loading state
    const resendForm = document.querySelector("form[action='{{ route('verification.send') }}']");
    if (resendForm) {
        resendForm.addEventListener('submit', function (e) {
            // Show loading state
            const resendButton = document.getElementById('resendButton');
            const buttonText = document.getElementById('buttonText');
            const buttonLoader = document.getElementById('buttonLoader');

            resendButton.disabled = true;
            buttonText.textContent = 'Mengirim ulang...';
            buttonLoader.classList.remove('hidden');
        });
    }
});
