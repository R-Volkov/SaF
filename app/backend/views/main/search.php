<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\CategoryList;
?>

<div class="row">
<div class="col-md-2"></div>
<div class="col-md-8">

	<?php if (Yii::$app->session->hasFlash('empty')): ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
  			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  			<?php echo Yii::$app->session->getFlash('empty'); ?>
		</div>
	<?php endif; ?>	

	<?php $form = ActiveForm::begin([
	'options' => ['enctype' => 'multipart/form-data',]]); ?>

	<?= $form->field($model, 'text')->textInput()->label('Полнотекстовый поиск') ?>
	<div class="col-md-6 bg-success">
		<?= $form->field($model, 'category_all')->checkbox()->label('Искать во всех категориях') ?>
		<br>
		<div class="form-group form-inline field-search-category_id">
			<label class="control-label">Искать в категории...</label>
			<input type="hidden" name="Search[category_id]" value="">
			<div id="search-category_id">
				<?= CategoryList::widget(['template' => 'checkBoxList']); ?>
			</div>
		</div>
	</div>
	<div class="col-md-6 bg-info">
		<?= $form->field($model, 'like_all')->checkbox()->label('Поиск совпадений в нижеперечисленном') ?>
		<?= $form->field($model, 'title')->checkbox()->label('Поиск совпадений в заголовке') ?>
		<?= $form->field($model, 'description')->checkbox()->label('Поиск совпадений в описании') ?>
		<?= $form->field($model, 'body')->checkbox()->label('Поиск совпадений в теле статьи') ?>
	</div>
	<div class="clearfix"></div>
	<?= $form->field($model, 'tags')->textInput()->label('Поиск по тегам') ?>

	<div class="form-group">
	        <?= Html::submitButton('Найти!', ['class' => 'btn btn-primary btn-block', 'value' => 1, 'name' => 'Find']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>


<div class="col-md-2"></div></div>

<div class="row" style="height: 600px;">
<div class="col-md-2" style="height: 100%;"></div>
<div class="col-md-4 text-primary" style="height: 100%; font-size: 16px;">
	<br>Ближайшие задачи:
		<br><del>- Обновление базы данных с введением подкатегорий;</del>
		<br><del>- Обновление названия папки с картинками при обновлении статьи;</del>
		<br><del>- Обновление главной картинки при обновлении статьи;</del>
		<br>- Вывод главной картинки при выводе статьи;
		<br><del>- Реализация надкатегорий в поиске;</del>
		<br><del>- Реализация поиска по тегам (одному или нескольким);</del>
		<br>- Реализация поиска по пользовательскому SQL-запросу;
		<br>- Поиск по авторам, "inwork", "show", и другим меткам;
		<br>- Добавление отложенной публикации;
		<br><del>- Подключение своих стилей и JS-кода;</del>
		<br><del>- Виджет "меню категорий";</del>
		<br><del>- Селект с виджетом "меню категорий";</del>
		<br><del>- Сохранение и обновление данных с селектом категорий;</del>
		<br><del>- Селектор множественного выбора в поиске по категориям;</del>
		<br>- Нормализация вида страницы поиска
		<br>
</div>
<div class="col-md-4 text-primary" style="height: 100%; font-size: 16px;">
	<br>Дальнейшие задачи:
		<br>
		<del><br>- Таблица пользователей;</del>
		<del><br>- Таблица админов;</del>
		<del><br>- Регистрация;</del>
		<del><br>- Авторизация и аутинтефикация;</del>
		<del><br>- Работа с сессиями и куками;</del>
		<del><br>- Ограничение доступа пользавателям к админке;</del>
		<del><br>- Введение ролей для админов;</del>
		<br>- Работа с таблицей админов (удаление, назначение, установка прав);
		<br>
		<br>- Создание таблицы тегов (список всех тегов и количество их упоминаний);
		<br>- Добавление в таблицу при появлении нового тега;
		<br>- Изменение кол-ва тегов при добавлении или удалении статьи;
		<br>- Изменение кол-ва тегов при обновлении тегов в статье;
		<br>- Удаление тега из таблицы, когда кол-во этого тега = 0 (использовать хранимые процедуры или триггеры);
		<br>- Облако тегов (необязательно);
		<br>- Вывод самых популярных тегов за период времени;
		<br>
		<br>- Интернационализация
		<br>
</div>
<div class="col-md-2" style="height: 100%;"></div></div>



<?= CategoryList::widget(['template' => 'horizontal']); ?>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>



