import './bootstrap';
import 'bootstrap'; // css

document.addEventListener('DOMContentLoaded', () => {
    initializeBackToTop();

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


function initializeBackToTop() {
    const backToTopBtn = document.getElementById("backToTop");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 300) {
            backToTopBtn.style.display = "block";
        } else {
            backToTopBtn.style.display = "none";
        }
    });

    backToTopBtn.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
}