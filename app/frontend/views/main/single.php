<?php 
use common\widgets\ArticleComments;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\models\Articles;
use yii\helpers\Url;
use common\widgets\SocialShare;
 ?>

<?php $this->title = $model->title ?>

<div class="single-page">
	<div class="sp-image-title">
		<div class="sp-info">
			<span>Автор: <?= $model->user->username; ?></span>
			<span>Категория: <?= $model->category->name; ?></span>
			<span class="">
				<?php
					Yii::$app->formatter->locale = 'ru-RU';
					echo Yii::$app->formatter->asDate($model->date_public, 'long'); 
				?>
			</span>
		</div>
		<h1 class="sp-title"><?= $model->title ?></h1>
		<img src="<?= Yii::getAlias('@uploads/') . $model->image ?>" class="sp-main-image">
	</div>

<?php  
	// echo SocialShare::widget([
	// 		'networks' => ['twitter'], 
	// 		'via_twitter' => 'almost_developer',
	// 		'title' => $model->title,
	// 		'description' => $model->description,
	// 		'url' => Url::to(['main/single', 'id' => $model->id], true),
	// 		'image_url' => Url::to(['@uploads/' . $model->title . "/" . $model->image], true),
	// 		'site_name' => Yii::$app->name,
	// 		'hashtags' => 'Some, tags, or, not',
	// 	]);
 ?>

	<div class="sp-body"><?= $model->body ?></div>
	<div class="sp-tags">
		<b>Тэги: </b>
		<?php foreach ($model->tags as $tag): ?>
			<a href="<?= Url::to(['/main/tag', 'name' => $tag->name]) ?>">
				<span class="label label-default"><?= $tag->name ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</div>

<?php if ($model->comments): ?>
	<div class="com">
		<?php Pjax::begin(['timeout' => 10000, 'id' => 'comments']); ?>

			<?= ArticleComments::widget(['article_id' => $model->id, 'article_title' => $model->title]); ?>

			<div class="row new-comment-wrapper">

				<?php if (!Yii::$app->user->isGuest): ?>
					<?php if (Yii::$app->user->identity['unlim_ban']): ?>
						<p>Вы навечно преданы анафеме. Досвидания</p>
					<?php elseif  (strtotime(Yii::$app->user->identity['ban_until']) > time()):?>
						<p>Вы забанены. Время окончания вашего бана - <?= Yii::$app->user->identity['ban_until']; ?></p>
					<?php else: ?>
						<?php $u_id = (Yii::$app->user->identity['id']); ?>
						<?php $form = ActiveForm::begin(['method' => 'POST', 'options' => 
											['enctype' => 'multipart/form-data', 'data-pjax' => "", 'class' => 'addComment', 'id' => 'CreateComment']
									  ]); ?>
								<?= $form->field($newComment, 'text')
										 ->textarea(['rows' => 6, 'class'=>'form-control responseInput', 'id' => 'responseInput', 'placeholder' => "Оставить комментарий..."])
										 ->label(false) ?>
								<?= $form->field($newComment, 'user_id')->hiddenInput(['value' => "$u_id"])->label(false) ?>
								<?= $form->field($newComment, 'article_title')->hiddenInput(['value' => $model->title])->label(false) ?>
								<?= $form->field($newComment, 'article_id')->hiddenInput(['value' => $model->id])->label(false) ?>
								<div class="row allImgWrapper">
									<div class="imageWrapper">
										<label class="addImage"><?= $form->field($newComment, 'ifiles[]', ['enableClientValidation' => false])
												 ->fileInput(['accept' => 'image/*', 'class' => 'imageInput'])
												 ->label(false) ?>
												<span class="btn btn-default btn-lg glyphicon glyphicon-camera" aria-hidden="true"></span>
										</label>
									</div>
								</div>
								<?= Html::submitButton('Отправить', ['class' => 'btn btn-success send', 'value' => 1, 'name' => 'addComment']) ?>
								<?= Html::Button('Обновить', ['class' => 'btn btn-primary refresh', 'value' => 1, 'name' => 'refresh']) ?>
						<?php ActiveForm::end(); ?>
					<?php endif; ?>
				<?php else: ?>
					<p class="comment-guest">Только зарегестрированные пользователи могут оставлять комментарии!</p>
				<?php endif; ?>
			</div>

		<?php Pjax::end(); ?>
	</div>
<?php else: ?>
	<div class="com well">
		<p class="forbidden-comment">Комментирование закрыто!</p>
	</div>
<?php endif; ?>

<div class="sp-image-modal">
	<div>
		
	</div>
</div>

<?php 
// $this->registerMetaTag([
//     'property' => 'og:title',
//     'content' => $model->title,
// ]);
// $this->registerMetaTag([
//     'property' => 'og:type',
//     'content' => 'article',
// ]);
// $this->registerMetaTag([
//     'property' => 'og:description',
//     'content' => substr($model->description, 0, 120) . '...',
// ]);
// $this->registerMetaTag([
//     'property' => 'og:url',
//     'content' => Url::to(['main/single', 'id' => $model->id], true),
// ]);
// $this->registerMetaTag([
//     'property' => 'og:url',
//     'content' => Url::to(['@uploads/' . $model->title . "/" . $model->image], true),
// ]);
// $this->registerMetaTag([
//     'name' => 'twitter:card',
//     'content' => 'summary_large_image',
// ]);
// $this->registerMetaTag([
//     'name' => 'twitter:site',
//     'content' => '@ViRuS_Z',
// ]);
// $this->registerMetaTag([
//     'name' => 'twitter:title',
//     'content' => $model->title,
// ]);
// $this->registerMetaTag([
//     'name' => 'twitter:description',
//     'content' => substr($model->description, 0, 120) . '...',
// ]);
// $this->registerMetaTag([
//     'name' => 'twitter:title',
//     'content' => $model->title,
// ]);
// $this->registerMetaTag([
//     'name' => 'twitter:image',
//     //'content' => Url::to(['@uploads/' . $model->title . "/" . $model->image], true),
//     'content' => 'http://www.llifle.com/photos/Ferocactus_latispinus_28234_l.jpg',
// ]);

 ?>
