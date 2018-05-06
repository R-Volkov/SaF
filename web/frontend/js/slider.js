$(document).ready(function() {

	//Начальный вызов необходимых функций для работы слайдера после загрузки страницы
	setTimeout(function(){
		$(window).trigger('resize');
		$('.slider .article-miniature:first').addClass('slider-active');
		changeFullSlide();
		adaptFullImage();
	}, 150);

	//Явное указание высоты блока из расчета его ширины
	$(window).resize(function() {
		var new_width = $('.slider').width();
		$('.slider').css('height', new_width/2.112);
	});

	//Основной цикл для работы слайдера
	var interval_marker = setInterval(function() {
		changeSlide();
	}, 5000);

	//меняет текущий маленький слайд
	function changeSlide() {
		var next_slide = $('.slider-active').next();
		$('.article-miniature').removeClass('slider-active');
		if (!next_slide.is('.article-miniature:last')){
			next_slide.addClass('slider-active');
		} else {
			$('.article-miniature:first').addClass('slider-active');
		}
		changeFullSlide();
		adaptFullImage();
	}

	//Меняет большой слайд
	function changeFullSlide() {
		var current_slide = $('.slider-active').html();
		$('.current-full > img').remove();
		$('.current-full').html(current_slide);
		$('.current-full > p').css("display", "block");		
	}

	//Подгоняет большую картинку, чтобы она занимала всю площадь блока независимо от соотношения сторон 
	function adaptFullImage() {
		if ($('.current-full > img').height() < $('.current-full').height()) {
			$('.current-full > img').css("height", '100%').css("width" , "auto");
		} else {
			$('.current-full > img').css("height", "auto").css("width" , "100%");
		}
	}

	//Обработка клика по миниатюре
	$('.article-miniature, .article-miniature > a > h1').click(function(event) {
		event.preventDefault();
		clearInterval(interval_marker);
		$('.article-miniature').removeClass('slider-active');
		$(this).filter('.article-miniature').addClass('slider-active');
		changeFullSlide();
		adaptFullImage();
	});


});