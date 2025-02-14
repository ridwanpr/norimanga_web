import './bootstrap';
import 'bootstrap'; // css
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

const notyf = new Notyf({
    duration: 3000,
    dismissible: true,
    position: {
        x: 'right',
        y: 'top',
    },
});

if (window.LaravelErrors && window.LaravelErrors.length > 0) {
    const errorMessage = window.LaravelErrors.join('<br>');
    notyf.error(errorMessage);
}

if (window.LaravelSuccessMessage) {
    notyf.success(window.LaravelSuccessMessage);
}

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