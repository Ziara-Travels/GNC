// import './bootstrap';
import '../css/app.css';
import 'swiper/swiper-bundle.css';
import 'animate.css';
import Swiper from 'swiper';
import AOS from 'aos';
import 'aos/dist/aos.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        offset: 100,
        once: true
    });

    // Initialize Swiper
    const swipers = document.querySelectorAll('.swiper-container');
    swipers.forEach(element => {
        new Swiper(element, {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            breakpoints: {
                640: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                }
            }
        });
    });

    // Initialize Bootstrap carousels
    const carouselElements = document.querySelectorAll('.carousel');
    if (carouselElements.length > 0 && typeof bootstrap !== 'undefined') {
        carouselElements.forEach(carouselElement => {
            new bootstrap.Carousel(carouselElement);
        });
    }
});
