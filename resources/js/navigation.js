        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburger-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (!hamburgerBtn || !mobileMenu) {
                return;
            }

            hamburgerBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                const isExpanded = hamburgerBtn.getAttribute('aria-expanded') === 'true';
                hamburgerBtn.setAttribute('aria-expanded', !isExpanded);
            });
        });
