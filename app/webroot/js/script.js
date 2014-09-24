$(function(){
	/* Main Slider */
	swiper = new Swiper('.swiper1', {
		pagination : '.pagination1'
	});
	
		/* Free mode: */
	var swiperFree = $('.swiper-free').swiper({
		pagination : '.pagination-free',
		freeMode : true,
		freeModeFluid: true,
		slidesPerSlide : 1,
		loop: true
	});
	
	/* Carousel mode: */
	var swiperCar = $('.swiper-car').swiper({
		pagination : '.pagination-car',
		slidesPerSlide : 3
	});


 
	
})