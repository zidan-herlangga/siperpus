
        document.addEventListener('DOMContentLoaded', function() {
            // Animate elements on scroll
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.animate-on-scroll');
                
                elements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementBottom = element.getBoundingClientRect().bottom;
                    
                    if (elementTop < window.innerHeight && elementBottom > 0) {
                        element.classList.add('animated');
                    }
                });
            };
            
            // Run once on page load
            animateOnScroll();
            
            // Run on scroll
            window.addEventListener('scroll', animateOnScroll);
            
            // Counter animation
            const counters = document.querySelectorAll('.counter');
            const speed = 200;
            
            const countUp = function() {
                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const increment = target / speed;
                    
                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(countUp, 10);
                    } else {
                        counter.innerText = target;
                    }
                });
            };
            
            // Start counter animation when element is in viewport
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.5
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        countUp();
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            if (counters.length > 0) {
                observer.observe(counters[0].parentElement.parentElement);
            }
        });