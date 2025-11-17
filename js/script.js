// Fix the navbar toggle and scroll behavior
let navbar = document.querySelector('.header .navbar')
let menuBtn = document.querySelector('#menu-btn')

menuBtn.onclick = () => {
    navbar.classList.toggle('active')
    menuBtn.classList.toggle('active')
}

window.onscroll = () => {
    navbar.classList.remove('active')
    menuBtn.classList.remove('active')
}

document.querySelectorAll('.contact .row .faq .box h3').forEach(faqBox => {
    faqBox.onclick = () => {
        faqBox.parentElement.classList.toggle('active')
    }
})

let swiper = new Swiper(".home-slider", {
    loop: true,
    effect: "coverflow",
    spaceBetween: 30,
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
        modifier: 2,
        slideShadows: true,
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    }
})

let swiper3 = new Swiper(".reviews-slider", {
    loop: true,
    slidesPerView: "auto",
    grabCursor: true,
    spaceBetween: 30,
    pagination: {
        el: ".swiper-pagination",
    },
    breakpoints: {
        768: {
            slidesPerView: 1,
        },
        991: {
            slidesPerView: 2,
        }
    }
})