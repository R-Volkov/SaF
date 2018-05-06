<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use common\widgets\CategoryList;
use kartik\datetime\DateTimePicker;
use vova07\imperavi\Widget;
use yii\helpers\Url;
use kartik\select2\Select2;
use common\models\Tag;
use yii\helpers\ArrayHelper;
use common\models\Categories;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'CreateForm']]); ?>

	<div class="col-md-8">
		<?= $form->field($model, 'body')->widget(Widget::className(), [
				'name' => 'redactor',
    			'settings' => [
        		'lang' => 'ru',
        		'minHeight' => 760,
        		'imageUpload' => Url::to(['/main/update']),
        		'convertVideoLinks' => true,
        		'iframe' => true,
        		// 'air' => true,
        		// 'airWidth' => '200px',
        		'plugins' => [
            		'clips',
            		'fullscreen',
        		]
    		]
		])->label('Текст статьи') ; ?>

		<div class="form-group">
		    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'value' => 1, 'name' => 'CreateArticle']) ?>
		    <?php 
		    if ($this->params['action'] == 'Update'):
		    	echo Html::a('Удалить', ['#'], ['data-toggle' => 'modal', 'data-target' => '#Delete', 'class' => 'btn btn-danger']);
			endif; ?>
		</div>

	</div>

	<div class="col-md-4">
		<?= $form->field($model, 'image_file')->fileInput(['id' =>'imgInp'])->label('Иллюстрация к статье') ?>
		<?= $form->field($model, 'title', ['enableClientValidation' => false])->textInput()->label('Основное заглавие') ?>
		<?= $form->field($model, 'title2')->textInput()->label('Дополнительное заглавие') ?>
		
		<?= $form->field($model, 'description')->textarea(['rows' => 7, 'cols' => 40])->label('Краткое описание') ?>

		<?= $form->field($model, 'category_id')->widget(Select2::classname(), [
			'data' => Categories::categoriesTree(),
		    'options' => ['placeholder' => 'Выберите категорию'],
		    'pluginOptions' => [
		        'allowClear' => true
		    ],
		])->label('Категория'); ?>
		<?php if ($model->image !=false): ?>
			<p class="has-image" hidden="true">src="<?php echo Yii::getAlias('@uploads/') ?><?= $model->image ?>"</p>
		<?php endif; ?>

		<?php 
			echo $form->field($model, 'date_public')->widget(DateTimePicker::classname(), [
				'value' => time(),
				'options' => ['placeholder' => 'Введите время публикации...'],
				'pluginOptions' => [
					'autoclose' => true
				]
			])->label('Время отложенной публикации');
		 ?>

		<?= $form->field($model, 'important')->checkbox(['label' => 'Главное']) ?>
		<?= $form->field($model, 'inwork')->checkbox(['label' => 'Отложить в черновики']) ?>
		<?= $form->field($model, 'comments')->checkbox(['label' => 'Разрешить комментирование']) ?>
		<?= $form->field($model, 'exhibit')->checkbox(['label' => 'Опубликовать на сайте']) ?>
	    <?= $form->field($model, 'tags_array')->widget(Select2::classname(), [
	            'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
	            'language' => 'ru',
	            'options' => ['placeholder' => 'Установите теги', 'multiple' => true],
	            'pluginOptions' => [
	                'allowClear' => true,
	                'tags' => true,
	                'maximumInputLength' => 100
	            ],
	        ])->label('Тэги'); ?>
	</div>

	<?php 
		Modal::begin([
				'options' => ['id' => 'Delete'],
				'header' => "<p class=\"text-center lead text-danger\">Вы действительно хотите удалить эту запись?</p>
				<p class=\"text-center lead\">$model->title</p>",
			]);
			echo Html::submitButton('Удалить', ['class' => 'btn btn-danger btn-block', 'value' => 3, 'name' => 'Delete']);
			echo Html::Button('Отмена', ['class' => 'btn btn-primary btn-block', 'data-dismiss' => 'modal', 'aria-hidden' => 'true']);
		Modal::end();
	?>

<?php ActiveForm::end(); ?>
