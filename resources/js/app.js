import './bootstrap';
import 'bootstrap'; // css

document.addEventListener('DOMContentLoaded', () => {
    let lastScroll = 0;
    const floatContainer = document.querySelector('.float-container');
    let scrollTimeout;

    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;

        clearTimeout(scrollTimeout);

        if (currentScroll > lastScroll) {
            floatContainer.classList.add('hidden');
        } else {
            floatContainer.classList.remove('hidden');
        }

        scrollTimeout = setTimeout(() => {
            floatContainer.classList.remove('hidden');
        }, 5000);

        lastScroll = currentScroll;
    });
});

