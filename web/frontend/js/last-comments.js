$(document).ready(function() {

	
	setTimeout(function(){
		$(window).trigger('resize');
	}, 150);

	//Явное указание высоты блока из расчета его ширины
	$(window).resize(function() {
		var comment_block_height = $('.index-left-block').height();
		$('.last-comments').css('height', comment_block_height-60);
	});

	//Основной цикл для обновления виджета
	var interval_marker = setInterval(function() {
		getNewComments();
	}, 60*1000);

	function getNewComments() {
		$.ajax({
			url : '/main/new-comments',
			accepts : {mytype : 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8'},
			data : {
				count : last_comment_widget_config.count,
			},
			timeout : 30000,
			type : 'GET',
			response : 'html',
			success : function(data) {
				ajaxDataHandler(data);
			}
		});
	}

	function ajaxDataHandler(data, textStatus) {
		if (data != '') {
			$('.last-comments > h2').after(data);
			last_comment_widget_config.count = $('.last-comments script:first').text();
		}
	}

});