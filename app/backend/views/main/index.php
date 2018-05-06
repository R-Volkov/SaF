<?php 
use common\widgets\CategoryList;
 ?>

<div class="col-md-2"></div>

<div class="col-md-8">

	<?php if (Yii::$app->session->hasFlash('default')): ?>
		<div class="alert alert-info alert-dismissible" role="alert">
  			<?php echo Yii::$app->session->getFlash('default'); ?>
		</div>
	<?php endif; ?>

</div>

<div class="col-md-2"></div>
