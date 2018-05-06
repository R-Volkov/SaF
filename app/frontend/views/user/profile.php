<?php  

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

$this->title = 'Profile';
?>

<div class="row">
    <div class="col-md-12 profile">
        <?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'form-horizontal']); ?>
			
			<div class="row">
				<div class="col-md-6">
					<p class="text-info" id="current-userpic-header">Текущее изображение пользователя:</p>
		            <?php if (Yii::$app->user->identity['userpic'] != false): ?>
	                  <img src="<?= Yii::getAlias('@uploads') . '/USERS//' . Yii::$app->user->identity['userpic'] ?>" alt="" id="current-userpic"'>
	                <?php else: ?>
	                  <p class="text-info" id="current-userpic-header">Изображение отсутствует</p>
	                <?php endif; ?>
				</div>
				<div class="col-md-6">
					<?= $form->field($model, 'raw_userpic')->widget(FileInput::classname(), [
						'options' => [
							'accept' => 'image/*'
						],
						'pluginOptions' => [
		        			'showCaption' => false,
		        			'showRemove' => false,
		        			'showUpload' => false,
		        			'browseClass' => 'btn btn-primary btn-block',
		        			'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
		        			'browseLabel' =>  'Выбрать изображение'
		    			],
		    		])->label(false); ?>					
				</div>
			</div>
			
			<br>
			
            <?= $form->field($model, 'username', ['options' => ['class' => 'profile-input']])->textInput()->label('Никнейм') ?>

            <?= $form->field($model, 'email', ['options' => ['class' => 'profile-input']])->textInput()->label('Почта') ?>

            <a class="btn btn-primary change-password" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            	Сменить пароль
            </a>

            <div class="collapse" id="collapseExample">
  				<div class="well">
		            <?= $form->field($model, 'old_password')->passwordInput()->label('Введите текущий пароль') ?>

		            <?= $form->field($model, 'new_password1')->passwordInput()->label('Введите новый пароль') ?>

		            <?= $form->field($model, 'new_password2')->passwordInput()->label('Повторите новый пароль') ?>
  				</div>
			</div>

            <div class="form-group">
            	<br><br><br>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

