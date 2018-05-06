jQuery(document).ready(function($) {

	var browser_height = window.innerHeight;
	$('body').css('minHeight', browser_height);

	$('p').has('iframe').css("height", "421px");


//Alteration of menu mode 
	var menu = $('.vertical');
	menu2 = $('.vertical > li > a');
	var menu_display_flag = $(".vertical > li").css("display");
    startAccordion();

    $(window).on('resize', function() {
    	if (menu_display_flag != $(".vertical > li").css("display")) {
    		menu_display_flag = startAccordion();
    	}	
    });

    function startAccordion() {
    	var menu_display = $(".vertical > li").css("display");

    	if (menu_display == "block") {
    		$('.li-vertCat').removeClass('dropdown');
    		$('.ul-vertCat').removeClass('dropdown-menu');
    		menu2.off();
	    	 menu.dcAccordion({
	      		eventType : 'click',
	      	});
	     	return (menu_display_flag = 'block');
	    } 
	    if (menu_display == "inline-block") {
	    	$('.li-vertCat').addClass('dropdown');
	    	$('.ul-vertCat').addClass('dropdown-menu');
	    	menu2.off();
	    	menu.dcAccordion({
	     		eventType : 'hover',
	    	});
	    	return (menu_display_flag = 'inline-block');
	    }
    }


	//getName();

	// function getName() {
	// 	$(".answer").each(function(ind, elem) {
	// 		var data_answer = $(this).data('respond');
	// 		var name = $('[data-id='+ data_answer +'] .name').text();
	// 		if ($(this).is('[data-respond]')) {
	// 			$(this).children("a").text(name);
	// 		}
	// 	});
	// };

//Modal for image in article
	$('.single-page > .sp-body > p > img').click(function(event){
		var img_html = $(this).parent().html();
		$('.sp-image-modal').children('div').html(img_html);
		$('.sp-image-modal').css({"display" : "block"});
	});

	$('.sp-image-modal > div').on('click', "img", function(event){
		event.stopPropagation();
	});

	$('.sp-image-modal > div').click(function(){
		$(this).parent().css({"display" : "none"});
	});

//User-friendly URL for search-form
	$('.search-submit').click(function(event) {
		var search_val = $('input[name="search"]').val();
		if (search_val != false) {
			event.preventDefault();
			var search_href = $('.search-link').attr('href');
			var new_href = search_href.replace('xxx', search_val);
			window.location.href = new_href;
		}
 	});

//PAJAX
	$('.com').on('click', ".refresh", function(){
         $.pjax.reload({container: '#comments'});
	 });

	//$("#comments").ajaxComplete(getName);


//Tip
	var timerId;
	var timerId2;
	$('.com').on('mouseenter', ".comment .answer", function() {
		$(".tip").parent().remove();
		$(".tip").remove();
		if (typeof timerId !== 'undefined') {
			clearTimeout(timerId);
		}
		var data_a = $(this).data('respond');
		var answer_code = $('[data-id='+ data_a +']').html();
		var final = "<div class='tip' data-id='" + data_a + "'>" + answer_code + "</div>";
		$(this).parents('.comment').before('<div class="tip-marker">' + final + '</div>');
	 });

	$('.com').on('click', ".tip .answer", function(event){
		event.stopPropagation();
	});

	function removeTip() {
		timerId = setTimeout(function(){
			$(".tip").parent().remove();
			$(".tip").remove();
		}, 1000);
	}

	$('.com').on('mouseleave', ".answer", function() {
		removeTip();
	});

	$('.com').on('mouseenter', ".tip", function() {
		if (typeof timerId !== 'undefined') {
			clearTimeout(timerId);
		}
		if (typeof timerId2 !== 'undefined') {
			clearTimeout(timerId2);
		}
	});

	$('.com').on('mouseleave', ".tip", function() {
		timerId2 = setTimeout(function(){
			$(".tip").parent().remove();
			$(".tip").remove();			
		}, 1000);
	});	

	//Кликаешь по всплывающему комменту - переходишь по якою к этому комменту
	$('.com').on('click', ".tip", function() {
		var id = $(this).data('id');
		$('[href = "#' + id + '"]:first').click();
	});

	$('.com').on('click',".anchor", function (event) {
        //отменяем стандартную обработку нажатия по ссылке
        event.preventDefault();
        //забираем идентификатор бока с атрибута href
        var id  = $(this).attr('href').slice(1);
        //узнаем высоту от начала страницы до блока на который ссылается якорь
        var top = $('.comment[data-id="' + id + '"]').offset().top;
        //анимируем переход на расстояние - top за 1500 мс
        $('body,html').animate({scrollTop: top-60}, 1000);
    });

	//Запуск модалки с картинкой и подстановка нужной инфы в модальный блок
	$('.com').on('click', ".comment-image", function(event){
		var image_path = $(this).attr('src');
		var image = '<a href="' + image_path + '" target="_blank" class="image-reference"><img class="modal-image" src="' + image_path + '"></a>';
		$('.modal-body').html(image);
		event.stopPropagation();
		$('#imageModal').modal({
		});
	});

	//Чтобы модалка не вешала сайт, когда кликаешь в модалке по картинке, чтобы открыть в новом окне
	$('.com').on('click', "a.image-reference", function(event){
		event.stopPropagation();
	});


//Image loading
	function readURL(input) {

	    if (input.files && input.files[0]) {
	        var reader = new FileReader();

	        reader.onload = function (e) {
	        	$(input).parents('.imageWrapper').prepend('<button class="removeImage">Remove image</button>');
	            $(input).parents('.imageWrapper').prepend('<img class="img-responsive" id="articleImage" src="' + e.target.result + '" alt="your image" />'); 
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$('.com').on('click', ".addImage", function() {
		InputElem = $(this).parents('.imageWrapper').clone();
	});

	$('.com').on('change', ".imageInput", function(){
	    readURL(this);
	    $(this).parents('label').css({"display" : "none"});

	    //Ограничиваем максимальное кол-во файлов до четырех
	    var files_counter = $('.imageInput').length;
	    if (files_counter == 4) {
	    	return false;
	    }
	    $(this).parents('.allImgWrapper').append($(InputElem));
	});

//Removing image
	$(".com").on('click', ".removeImage", function() {
		$(this).parents('.imageWrapper').remove();

		var files_counter = $('.img-responsive').length;
	    if ((files_counter == 3)) {
	    	$('.allImgWrapper').append(InputElem);
	    }
	});

//Respond
	$(".com").on('click', ".respond", function(event) {
		event.stopPropagation();
		var data_id = $(this).parents('.comment').data('id');
		var data_name = $(this).parents('.comment').find("p.name").text();
		var rI = $(".responseInput");
		var new_val = ' >>' + data_id + '-' + data_name + ', ';
		insertTextAtCursor(document.getElementById('responseInput'), new_val);
	});

	function insertTextAtCursor(el, text, offset) {
	    var val = el.value, endIndex, range, doc = el.ownerDocument;
	    if (typeof el.selectionStart == "number"
	            && typeof el.selectionEnd == "number") {
	        endIndex = el.selectionEnd;
	        el.value = val.slice(0, endIndex) + text + val.slice(endIndex);
	        el.selectionStart = el.selectionEnd = endIndex + text.length+(offset?offset:0);
	    } else if (doc.selection != "undefined" && doc.selection.createRange) {
	        el.focus();
	        range = doc.selection.createRange();
	        range.collapse(false);
	        range.text = text;
	        range.select();
	    }
	}

	//Открытие Collapse для профиля пользователя если при смене пароля пароли были введены неправильно
	if (($('p.help-block-error').text()) != false) {
		$(".change-password").click();
	} 


});
