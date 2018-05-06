<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'ChangeRoleForm']]); ?>
	
	<div class="col-md-4">
		<?= $form->field($model, 'role', [])->dropDownList(['user' => 'Просто пользователь', 'admin' => 'Админ', 'moderator' => 'Модератор'])->label('Выбрать роль') ?>
		<?= Html::submitButton('Подтвердить новую роль', ['class' => 'btn btn-primary']) ?>
	</div>

<?php ActiveForm::end(); ?>