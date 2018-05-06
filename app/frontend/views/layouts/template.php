<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\ActiveForm;
use common\widgets\CategoryList;
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
        <title><?= Html::encode($this->title ? $this->title : Yii::$app->name) ?></title>
        <?php $this->head(); ?>    
    </head>

    <body>
      <?php $this->beginBody(); ?> 
    
      <nav class="navbar navbar-inverse">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="<?= Url::home() ?>"><span class="headText"><span class="siteAbbreviation"></span><p class="siteTitle">Science and Fiction</p></span></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
                <?php if (Yii::$app->user->isGuest): ?>
                  <li><a href="<?= Url::to(['/user/signup']) ?>">SignUp</a></li>
                  <li><a href="<?= Url::to(['/user/login']) ?>">Login</a></li>
                <?php else: ?>
                  <li><a href="<?= Url::to(['/user/logout']) ?>">Logout</a></li>
                <?php endif; ?>
                <?php if (!Yii::$app->user->isGuest): ?>
                  <li><a href="<?= Url::to(['/user/profile', 'id' => Yii::$app->user->identity['id']]) ?>" class="">
                      <?= Yii::$app->user->identity['username'] ?></a></li>
                  <li>
                    <?php if (Yii::$app->user->identity['userpic'] != false): ?>
                      <?php if (file_exists(Yii::getAlias('@uploads') . '/USERS/small_' . Yii::$app->user->identity['userpic'])): ?>
                        <img src="<?= Yii::getAlias('@uploads') . '/USERS/small_' . Yii::$app->user->identity['userpic'] ?>" alt="" id="userpic"'>
                      <?php else: ?>
                        <img src="<?= Yii::getAlias('@uploads') . '/USERS/' . Yii::$app->user->identity['userpic'] ?>" alt="" id="userpic"'>
                      <?php endif ?>
                      
                    <?php else: ?>
                      <img src="<?= Yii::getAlias('@uploads') . '/USERS//' . 'placeholder.jpg' ?>" alt="" id="userpic"'>
                    <?php endif; ?>
                  </li>
                <?php endif; ?>
              </ul>
              <form class="navbar-form navbar-right" action="/main/search">
                <div class="form-inline">
                  <div class="form-group">
                    <div class="input-group input-group-sm">
                      <input type="text" name="search" class="form-control" placeholder="Поиск" aria-describedby="inputGroupSuccess3Status">
                      <a href="<?= Url::to(['/main/search', 'search' => 'xxx']) ?>" class="search-link"></a>
                      <span class="input-group-btn"><button type="submit" class="btn btn-default search-submit"><span class="glyphicon glyphicon-search"></span></button></span>
                    </div>
                  </div>
                </div>     
              </form>

            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>

        <div class="affix-menu" data-spy="affix" data-offset-top="50">
          <div class="second-menu">
              <?= CategoryList::widget(['template' => 'vertical']); ?>
          </div>
        </div>
    
        <div class="clearfix"></div>

        <div class="container main-content">
          <?php if (Yii::$app->session->hasFlash('success')): ?>
              <div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <?php echo Yii::$app->session->getFlash('success'); ?>
              </div>
          <?php endif; ?>

          <?php if (Yii::$app->session->hasFlash('failure')): ?>
              <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <?php echo Yii::$app->session->getFlash('failure'); ?>
              </div>
          <?php endif; ?>

          <?= $content ?>
        </div>

        <footer class="footer">
            <div class="container">
                <span class="badge">
                    <span class="glyphicon glyphicon-copyright-mark"> GAMEFLAME <?= date('Y') ?></span>
                </span>
            </div>
        </footer>

    <?php $this->endBody(); ?>    
    </body>

</html>

<?php $this->endPage(); ?>