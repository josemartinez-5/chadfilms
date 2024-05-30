const carouselFilmsElement = document.querySelector('#carouselFilms')
const carousel = new bootstrap.Carousel(carouselFilmsElement, {
})

const firstIndicator = carouselFilmsElement.querySelector('.carousel-indicators > button')
firstIndicator.setAttribute("class", "active")
firstIndicator.setAttribute("aria-current","true")

const firstSlide = carouselFilmsElement.querySelector('.carousel-inner > .carousel-item')
firstSlide.classList.add('active')

carousel.cycle()