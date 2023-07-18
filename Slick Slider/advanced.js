jQuery(function ($) {
	$(document).ready(function () {
		const mobile = {
			breakpoint: 768,
			settings: {
				slidesToShow: 1,
				vertical: true
			}
		}

		let slides = [
			'.camera-slide',
			'.lighting-slide',
			'.audio-slide',
			'.office-slide'
		]

		slides = slides.map(s => {
			const selector = `.cstm-slider${s}`
			const slider = $(`${selector} .cstm-slider-inner`)
			slider.css('display', 'inline-block')

			return slider.slick({
				dots: false,
				nextArrow: $(`${selector} .next-slide`),
				prevArrow: $(`${selector} .prev-slide`),
				speed: 300,
				autoplay: false,
				autoplaySpeed: 2000,
				slidesToShow: 2,
				slidesToScroll: 1,
				responsive: [mobile]
			});
		})
	});
});