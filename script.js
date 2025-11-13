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
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto", // Allows slides to have different widths
    coverflowEffect: {
      rotate: 0,
      stretch: 0,
      depth: 100,
      modifier: 2,
      slideShadows: true,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true, // Enable clicking on pagination bullets
    },
    // Enable touch/swipe controls
    touchRatio: 1,
    touchAngle: 45,
    simulateTouch: true,
    // Optional: Add navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    // Optional: Keyboard navigation
    keyboard: {
      enabled: true,
    },
    // Optional: Mousewheel navigation
    mousewheel: {
      forceToAxis: true,
    }
  });