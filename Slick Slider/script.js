jQuery(function($) {
    $(document).ready(function() {
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

        $('.targetElement').slick({
            dots: false,
            arrows: true,
            nextArrow: $('#next-arrow-square'),
            prevArrow: $('#prev-arrow-square'),
            speed: 300,
            autoplay: false,
            autoplaySpeed: 2000,
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [tablet, mobile]
        });
    });
});