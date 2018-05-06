<?php  

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->registerCssFile('@web/css/singin.css', ['depends' => [frontend\assets\AppAsset::className()],]);
?>
<div class="container">

<!--             <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'password2')->passwordInput()->label('Repeat the password') ?>

                <?= $form->field($model, 'remember')->checkBox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?> -->

            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['class' => 'form-signin'],]); ?>

                <h2 class="form-signin-heading">Регистрация</h2>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'id' => 'usernameInput'])->label('Имя пользователя') ?>

                <?= $form->field($model, 'email')->label('E-mail') ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

                <?= $form->field($model, 'password2')->passwordInput()->label('Повторите пароль') ?>

                <?= $form->field($model, 'remember')->checkbox()->label('Запомнить меня') ?>

                <div class="form-group">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
</div>