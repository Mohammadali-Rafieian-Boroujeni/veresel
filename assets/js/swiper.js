document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".vsl-carousel").forEach((carousel) => {

        const slider = carousel.querySelector(".vsl-swiper");

        new Swiper(slider, {

            direction: "horizontal",

            grabCursor: true,

            simulateTouch: true,

            watchOverflow: true,

            speed: 450,

            spaceBetween: 12,

                navigation: {

                    nextEl: carousel.querySelector(".vsl-prev"),

                    prevEl: carousel.querySelector(".vsl-next")

                },

            breakpoints: {

                0: {

                    slidesPerView: 1.15,

                    spaceBetween: 10

                },

                480: {

                    slidesPerView: 2

                },

                768: {

                    slidesPerView: 3

                },

                1024: {

                    slidesPerView: 4

                },

                1440: {

                    slidesPerView: 5

                }

            }

        });

    });

});