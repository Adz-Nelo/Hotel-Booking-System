let navbar = document.querySelector('.header .navbar')

document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active')
}

window.onscroll = () => {
    navbar.classList.toggle('active')
}

let swiper = new Swiper(".home-slider", {
    loop: true,
    effect: "coverflow",
    grabCursor: true,
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    }
})

let swiper2 = new Swiper(".gallery-slider", {
    loop: true,
    effect: "coverflow",
    slidesPerView: "auto",
    centeredSlides: true,
    grabCursor: true,
    coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
    },
    pagination: {
        el: ".swiper-pagination",
    }
})