<?php

use yii\helpers\Html;
use backend\assets\AppAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

AppAsset::register($this);
?>

<?php $this->beginPage(); ?>
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
        <div class="wrap">
            <div class="container-fluid">
                <ul class="nav nav-justified">
                    <li><a href="<?php echo Url::to(['/main/about']); ?>">About</a></li>
                    <li><a href="<?php echo Url::to(['/main/all']); ?>">All articles</a></li>
                    <li><a href="<?php echo Url::to(['/main/create']); ?>">Create article</a></li>
<!--                     <li><a href="<?php echo Url::to(['/main/drafts']); ?>">Drafts</a></li>
                    <li><a href="<?php echo Url::to(['/main/deferred']); ?>">Deferred</a></li> -->
                    <li><a href="<?php echo Url::to(['/moderation/mod']); ?>">Moderation</a></li>
                    <li><a href="<?php echo Url::to(['/users/users']); ?>">Users</a></li>
                    <li><a href="<?php echo Url::to(['/users/admins']); ?>">Admins</a></li>
<!--                     <li><a href="<?php echo Url::to(['/category/index']); ?>">Category management</a></li>
                    <li><a href="<?php echo Url::to(['/admins/message']); ?>">Write to superadmin</a></li> -->
                    <li class="logout"><a href="<?php echo Url::to(['/entry/logout']); ?>">Logout</a></li>
                </ul>

                <div class="col-md-12 contentContainer">
                    <?php if (Yii::$app->session->hasFlash('failure')): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('failure'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo Yii::$app->session->getFlash('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?= $content ?>  
                </div>

            </div>
        </div>
    <?php $this->endBody(); ?>    
    </body>
</html>
<?php $this->endPage(); ?>