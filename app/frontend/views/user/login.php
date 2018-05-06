<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->registerCssFile('@web/css/singin.css', ['depends' => [frontend\assets\AppAsset::className()],]);
?>

<div class="container">

    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-signin'],]); ?>

        <h2 class="form-signin-heading">Выполните вход</h2>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'id' => 'usernameInput'])->label('Имя пользователя') ?>

        <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

        <?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня') ?>

        <div style="color:#999;margin:1em 0">
            Если вы забыли пароль, вы можете <?= Html::a('сбросить его', ['site/request-password-reset']) ?>.
        </div>

        <div class="form-group">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
