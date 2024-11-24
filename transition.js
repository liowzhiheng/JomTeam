document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.transition-container');

    if (container) {
        // Add the enter animation class on load
        container.classList.add('page-transition-enter');
        setTimeout(() => {
            container.classList.add('page-transition-enter-active');
        }, 10); // Slight delay to trigger transition

        // Handle navigation links
        document.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#')) return; // Ignore empty links or anchors

                e.preventDefault(); // Prevent default navigation
                startPageTransition(() => {
                    window.location.href = href; // Navigate after animation
                });
            });
        });

        // Handle form submissions
        document.querySelectorAll('form').forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault(); // Prevent default submission
                startPageTransition(() => {
                    form.submit(); // Submit the form after animation
                });
            });
        });
    }

    function startPageTransition(callback) {
        // Start the leave animation
        container.classList.add('page-transition-leave');
        container.classList.add('page-transition-leave-active');

        // Wait for the animation to complete before executing the callback
        setTimeout(() => {
            callback();
        }, 600); // Match the animation duration in CSS
    }
});
