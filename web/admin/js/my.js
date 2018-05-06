$(document).ready(function() {
    $('.vertical').dcAccordion({
    	speed : 250,
    });
    insertExistingImage();

    //Выделение активного пункта меню
    var url = document.location.href;
    var nav_url = url.match(/\/admin\/(\w+\/\w+)/);
    $('ul.nav > li > a[href *= "' + nav_url[1] + '"]').parent().addClass('active');

//Moderation autoupdate
    //Костыль, чтобы самопальный комбобокс нормально отображал выпадающий список после первого ввода
    $('.update-timer').click(function() {
      $(this).val('');
    });

    $('.moderation-form button').click(function(event) {
      event.preventDefault();
      $.pjax.reload({container: '#moderation'});
    });

    //Получаем значение времени из инпута
    function getTime() {
      var update_time;
      var ut_val = $('.update-timer').val();
      if (ut_val !== "") {
        switch (ut_val) {
          case '30 секунд':
            update_time = 30000;
            break;
          case '1 минута':
            update_time = 60000;
            break;
          case '2 минуты':
            update_time = 120000;
            break;
          case '5 минут':
            update_time = 300000;
            break;
          case '10 минут':
            update_time = 600000;
            break;
          default:
            var user_time = ut_val * 1000;
            //На случай ввода пользователем не числа
            if (isNaN(user_time)) {
              return false;
            } else {
              update_time = user_time;
            }
            break;     
        }
      } else {
        return false;
      }
      return update_time; 
    }

    //Рекурсивно вызываем таймер через заданный промежуток
    var timerId;
    function moderationUpdater(time) {
      timerId = setTimeout(function(){
        //alert('hello! ' + $('.update-timer').val());
        $('.moderation-form button').click();
        moderationUpdater(time);
      }, time);
    }

    //При изменении инпута получает новое значение времени, удаляет старый обработчик и вызывает новый с новым временем
    $('.update-timer').change(function() {
      //alert(getTime());
      var time = getTime();
      if (time != false) {
        if (typeof timerId !== 'undefined') {
          clearTimeout(timerId);
        }
        moderationUpdater(time);
      } else {
        //Если пользователь полностью удалил значение инпута снимаем обработчик 
        if (typeof timerId !== 'undefined') {
          clearTimeout(timerId);
        } 
      }
    });


//GridWiev
    $('.p-grid-all, .m-grid-all, .u-grid-all').on('click', ".show-hide-image", function(){
      if ($(this).siblings('img').attr('hidden') == 'hidden') {
        $(this).siblings('img').attr('hidden', false);
        $(this).text('Скрыть');
        return false;
      } else {
        $(this).siblings('img').attr('hidden', true);
        $(this).text('Показать');
        return false;
      }
    });

    $('.p-grid-all, .m-grid-all, .u-grid-all').on('click', ".show-all-images", function(){
      if ($(this).data("show") == true) {
        $(this).data("show", false);
        $(this).text('Скрыть все');
        $('.grid-view .grid-view-image').attr('hidden', false);
        $('.grid-view .show-hide-image').text('Скрыть');
        return false;
      } else {
        $(this).data("show", true);
        $(this).text('Показать все');
        $('.grid-view .grid-view-image').attr('hidden', true);
        $('.grid-view .show-hide-image').text('Показать');
        return false;
      }
    });

    //Запуск модалки с картинкой и подстановка нужной инфы в модальный блок
    $('.articles-index').on('click', ".grid-view-image", function(event){
      var image_path = $(this).attr('src');
      image_path = image_path.replace(/\/small_/, '/');
      var image = '<a href="' + image_path + '" target="_blank" class="image-reference"><img class="modal-image" src="' + image_path + '"></a>';
      $('#imageModal .modal-body').html(image);
      event.stopPropagation();
      $('#imageModal').modal({
      });
    });

    //Чтобы модалка не вешала сайт, когда кликаешь в модалке по картинке, чтобы открыть в новом окне
    $('.articles-index').on('click', "a.image-reference", function(event){
      event.stopPropagation();
    });

});

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            //$('#blah').attr('src', e.target.result);
            $("#articleImage").remove();
            $("#imgInp").before('<img class="img-responsive" id="articleImage" src="' + e.target.result + '" alt="your image" />');
            if (!$("button").is(".removeImage")) {
            	$("#imgInp").after('<button class="removeImage btn btn-danger btn-sm" onclick="removeImage()">Удалить изображение</button>');
            }   
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#imgInp").change(function(){
    readURL(this);
});

function removeImage() {
	$("#articleImage").remove();
	ClearFile('CreateForm');
	$(".removeImage").remove();
}


function ClearFile(idForm)
{
   var form = document.getElementById(idForm);
   //Массив значений всех элементов формы 
   var values = new Array(form.elements.length);
 
   //Запись значений всех элементов формы 
   for (var i = 0; i < form.elements.length; i++) 
   {
      values[i] = form.elements.item(i).value;
   }
    
 
   form.reset(); //Сброс значений всех элементов формы 
 
   //Восстановление значений всех элементов формы, кроме input file 
   for (var i = 0; i < form.elements.length; i++)
   {
      //Здесь сравнивается тип, т.к. используется один input file
      //Если элементов input file больше, то нужно использовать id
      if(form.elements.item(i).type != 'file')
      {
         form.elements.item(i).value = values[i];
      }
   }
}

function insertExistingImage() {
  if ($("p.has-image").text() != false) {
    var image = $("p.has-image").text();
    $('[for = "imgInp"]').after('<img class="img-responsive" id="articleImage"' + image +'>');
    $('input[type="file"]').after('<button class="removeImage btn btn-danger btn-sm" onclick="removeImage()">Удалить изображение</button>');
  }
}



//Modal ban

$('#banModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var userId = button.data('userid');
  var username = button.data('username');

  $(this).find('span.userId').text(userId);
  $(this).find('span.username').text(username);
  $(this).find('[name=userId]').val(userId);
});

$('#time_for_ban').change(function() {
    var until = $('#time_for_ban').val();
    $('[name=date_for_ban]').val(until);
});

$('.temporaryBan').click(function() {
  userId = $('span.userId').text();
  var date_for_ban = $('[name=date_for_ban]').val();
  var hrefBan = '/admin/moderation/ban?id=' + userId + '&until=' + date_for_ban;
  $('a.temporaryBan').attr("href", hrefBan);
});

$('.banForever').click(function() {
  userId = $('span.userId').text();
  var hrefBan = '/admin/moderation/ban?id=' + userId + '&until=';
  $('a.banForever').attr("href", hrefBan);
});

//Modal ban end
