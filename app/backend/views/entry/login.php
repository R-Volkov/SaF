<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\AppAsset;
AppAsset::register($this);

$this->beginPage();
$this->title = 'Login';
$this->registerCssFile('@web/css/singin.css', ['depends' => [backend\assets\AppAsset::className()],]);
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>   
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head(); ?>    
    </head>

    <body>
        <?php $this->beginBody(); ?> 

        <div class="container">

            <?php if (Yii::$app->session->hasFlash('failure')): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo Yii::$app->session->getFlash('failure'); ?>
                </div>
            <?php endif; ?>

            <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-signin'],]); ?>

                <h2 class="form-signin-heading">Выполните вход</h2>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'id' => 'usernameInput'])->label('Имя пользователя') ?>

                <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

                <?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня') ?>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>

    <?php $this->endBody(); ?>    
    </body>
</html>
<?php $this->endPage(); ?>