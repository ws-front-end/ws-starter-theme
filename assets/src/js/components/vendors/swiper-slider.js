import { Swiper, Lazy, Autoplay, Navigation } from 'swiper/js/swiper.esm.js';

import 'swiper/css/swiper.css';

Swiper.use([Lazy, Autoplay, Navigation]);

new Swiper('.slider__container', {
  preloadImages: false,
  lazy: {
    loadPrevNext: true,
  },
  autoplay: {
    delay: 4000,
  },
  loop: true,
  speed: 1500,
  disableOnInteraction: false,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
});
