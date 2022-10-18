// Add your custom JS here.

var body = jQuery('body');

jQuery(document).ready(function($) {

  $('#liderlamp-nav .navbar-toggler').click(function(e) {
    body.toggleClass('menu-abierto');
  });

	$('.tinvwl-table-manage-list').addClass('shop_table shop_table_responsive');

  $('#search-collapse').on('shown.bs.collapse', function () {
    $('#search-collapse #woocommerce-product-search-field-0').focus();
  });

  $('.shipping-calculator-form input[type="text"], .woocommerce-checkout input[type="text"], .woocommerce-checkout input[type="email"]').addClass('form-control');

});

var dragViewport = document.querySelector('.drag-viewport');
if( dragViewport !== null ) {
	new ScrollBooster({
	  viewport: dragViewport,
	  content: document.querySelector('.drag-content'),
	  scrollMode: 'transform', // use CSS 'transform' property
	  direction: 'horizontal', // allow only horizontal scrolling
	  emulateScroll: true, // scroll on wheel events
	  bounceForce: 0.2,
	});
}

jQuery('.slick-carousel').slick({
  dots: false,
  arrows: true,
  infinite: false,
  speed: 300,
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 3500,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

jQuery('.slick-carrusel-posts').slick({
  dots: false,
  arrows: false,
  infinite: true,
  speed: 300,
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 3500,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

jQuery('.slick-carousel-woocommerce > .wp-block-group__inner-container').slick({
  dots: false,
  arrows: false,
  infinite: true,
  speed: 300,
  slidesToShow: 5,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 3000,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

jQuery('.carrusel-categoria').slick({
  dots: false,
  arrows: true,
  // appendArrows: '.carrusel-categoria-presentacion',
  infinite: true,
  speed: 300,
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 5000,
  responsive: [
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 783,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});


jQuery('.slick-home-trastienda').slick({
  dots: false,
  arrows: false,
  infinite: true,
  speed: 300,
  slidesToShow: 2,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 3000,
  responsive: [
    {
      breakpoint: 9999,
      settings: "unslick",
    },
    {
      breakpoint: 783,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});


jQuery('.slick-instagram').slick({
  dots: true,
  arrows: false,
  infinite: true,
  speed: 300,
  slidesToShow: 3,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 3000,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

jQuery('#top-bar > .container-fluid').slick({
  dots: false,
  arrows: false,
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 5000,
  vertical: true,
});

jQuery('.slider-novedades, .slider-destacados, .slider-testimonios, .slider-cabecera-term').slick({
  dots: false,
  arrows: true,
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 5000,
});

jQuery('.carrusel-blog').slick({
  dots: false,
  arrows: true,
  infinite: true,
  speed: 300,
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 4000,
  variableWidth: true,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});


