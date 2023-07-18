jQuery(function($) {
    $(document).ready(function() {
        const selector = $('.targetElement') //Change this
        const tablet = {
            breakpoint: 1025,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        }
        const mobile = {
            breakpoint: 768,
            settings: {
                slidesToShow: 1
            }
        }

        selector.css('display', 'inline-block') // Prevents display Error
        selector.slick({
            dots: true,
            arrows: true,
            speed: 300,
            infinite: true,
            autoplay: false,
            autoplaySpeed: 2000,
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [tablet, mobile]
        });
    });
});