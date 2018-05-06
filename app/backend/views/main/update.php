<?php $this->params['action'] = 'Update'; ?>

<div class="col-md-10">

	<?php if (Yii::$app->session->hasFlash('sucsess')): ?>
		<div class="alert alert-success alert-dismissible" role="alert">
  			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  			<?php echo Yii::$app->session->getFlash('sucsess'); ?>
		</div>
	<?php endif; ?>

	<?= $this->render('cuform', ['model' => $model]); ?>

</div>

<div class="col-md-2"></div>