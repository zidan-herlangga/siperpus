// ---------- GLOBAL FUNCTIONS ----------
function animateCounter(element, target, duration = 2000) {
    if (!element) return;
    let start = 0;
    const increment = target / (duration / 16);
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target + "+";
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start) + "+";
        }
    }, 16);
}

// function showBorrowModal() {
//     document.getElementById("borrowModal")?.classList.remove("hidden");
//     document.body.style.overflow = "hidden";
// }

// function closeBorrowModal() {
//     document.getElementById("borrowModal")?.classList.add("hidden");
//     document.body.style.overflow = "auto";
// }

function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector("i");
    if (!input || !icon) return;

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}


// ---------- DOMContentLoaded ----------
document.addEventListener("DOMContentLoaded", function () {
    // 1. Mobile menu toggle
    const mobileMenuButton = document.getElementById("mobileMenuButton");
    const mobileMenu = document.getElementById("mobileMenu");
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener("click", function () {
            mobileMenu.classList.toggle("hidden");
            const icon = this.querySelector("i");
            if (mobileMenu.classList.contains("hidden")) {
                icon.classList.replace("fa-times", "fa-bars");
            } else {
                icon.classList.replace("fa-bars", "fa-times");
            }
        });
    }

    // 2. Add fade-in animation to main content
    const mainContent = document.querySelector("main");
    if (mainContent) {
        mainContent.classList.add("fade-in");
    }

    // 3. Verification Modal Logic
    const modal = document.getElementById("verificationModal");
    if (modal) {
        const modalContent = modal.querySelector(".modal-content");
        const closeModalBtn = document.getElementById("closeModalBtn");

        function showModal() {
            modal.classList.remove("hidden");
            void modalContent.offsetWidth;
            modalContent.classList.remove("scale-95", "opacity-0");
            modalContent.classList.add("scale-100", "opacity-100");
        }

        function hideModal() {
            modalContent.classList.add("scale-95", "opacity-0");
            setTimeout(() => modal.classList.add("hidden"), 300);
        }

        showModal(); // Show modal on page load if it exists

        closeModalBtn?.addEventListener("click", hideModal);
        modal.addEventListener("click", function (event) {
            if (event.target === modal) hideModal();
        });
    }

    // 4. Start animated counters
    setTimeout(() => {
        animateCounter(document.getElementById("bookCount"), 5000);
        animateCounter(document.getElementById("studentCount"), 1200);
        animateCounter(document.getElementById("borrowCount"), 350);
        animateCounter(document.getElementById("categoryCount"), 50);
    }, 500);

    // 5. Scroll-triggered animation
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("animate-fade-in-up");
            }
        });
    }, observerOptions);

    document.querySelectorAll(".grid > div").forEach((card) => {
        observer.observe(card);
    });

    // 6. Fade-in animation for white backgrounds
    const whiteElements = document.querySelectorAll(".bg-white");
    whiteElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 100}ms`;
        element.classList.add("animate-fade-in-up");
    });

    // 7. Registration form submit + validation
    const registrationForm = document.getElementById("registrationForm");
    const registrationSubmitBtn = document.getElementById("submitButton");

    if (registrationForm) {
        registrationForm.addEventListener("submit", function (e) {
            const originalText = registrationSubmitBtn.innerHTML;
            registrationSubmitBtn.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-2"></i>Mendaftarkan...';
            registrationSubmitBtn.disabled = true;

            setTimeout(() => {
                registrationSubmitBtn.innerHTML = originalText;
                registrationSubmitBtn.disabled = false;
            }, 3000);
        });

        const inputs = document.querySelectorAll("input, select");
        inputs.forEach((input) => {
            input.addEventListener("blur", function () {
                validateField(this);
            });

            input.addEventListener("input", function () {
                if (this.classList.contains("border-red-300")) {
                    validateField(this);
                }
            });
        });

        function validateField(field) {
            if (field.hasAttribute("required") && field.value.trim() === "") {
                field.classList.add("border-red-300", "bg-red-50");
                field.classList.remove("border-gray-300");
            } else {
                field.classList.remove("border-red-300", "bg-red-50");
                field.classList.add("border-gray-300");

                if (field.type === "email" && field.value.trim() !== "") {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(field.value)) {
                        field.classList.add("border-red-300", "bg-red-50");
                    }
                }
            }
        }

        const formElements = document.querySelectorAll("input, select, button");
        formElements.forEach((element, index) => {
            element.style.animationDelay = `${index * 50}ms`;
            element.classList.add("animate-fade-in-up");
        });
    }

    // 8. Login form loading state
    const loginForm = document.getElementById("loginForm");
    const loginSubmitBtn = document.getElementById("submitButton");

    if (loginForm && loginSubmitBtn) {
        loginForm.addEventListener("submit", function () {
            loginSubmitBtn.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            loginSubmitBtn.disabled = true;
        });
    }

    // 9. Auto-submit on category change
    const categorySelect = document.getElementById("category");
    if (categorySelect) {
        categorySelect.addEventListener("change", function () {
            this.form.submit();
        });
    }

    // 10. Add loading state to search
    const searchForm = document.querySelector("form");
    if (searchForm) {
        searchForm.addEventListener("submit", function () {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...';
            submitBtn.disabled = true;

            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1000);
        });
    }

    // 11. Scroll animation for book cards
    const bookCardObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = `${
                        entry.target.dataset.delay || 0
                    }ms`;
                    entry.target.classList.add("animate-fade-in-up");
                }
            });
        },
        { threshold: 0.1 }
    );

    document.querySelectorAll(".book-card").forEach((card, index) => {
        card.style.opacity = "0";
        card.dataset.delay = index * 100;
        bookCardObserver.observe(card);
    });

    // 12. Guide Book
    function openBorrowGuideModal() {
            document.getElementById('borrowGuideModal').classList.remove('hidden');
    }
    function closeBorrowGuideModal() {
        document.getElementById('borrowGuideModal').classList.add('hidden');
    }
});
