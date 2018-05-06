<?php
use yii\helpers\Url;
use common\widgets\Slider;
use common\widgets\LastComments;
?>

<div class="col-md-9 index-left-block">
	<div class="slider-widget">
		<?= 
			Slider::widget([
				'articles_array' => $main,
			]);
		?>
	</div>

	<div class="last-articles">
		<?php foreach ($last as $article): ?>
			<div class="row search-result">
				<div class="col-md-4 sr-image">
					<?php if ($article['image']): ?>
						<img src="<?= Yii::getAlias('@uploads/') . 'SMALL_' . $article['image']; ?>" class="search-result-image">
					<?php endif; ?>
				</div>
				<div class="col-md-8 sr-article-info">
					<div class="sr-header clearfix">
						<a href="<?= Url::to(['/main/single', 'id' => $article['id']]); ?>"><h3 class="sr-title"><?= $article['title']; ?></h3></a>
<!-- 						<p class="pull-right sr-comment">
							<span class="glyphicon glyphicon-comment sr-comment-icon"></span>
							 <?= $article['comment_count'] ?>
						</p> -->
						<p class="sr-author pull-right">
							Автор: 
							<a href="<?= Url::to(['/main/author', 'name' => $article['user']['username']]) ?>"><?= $article['user']['username']; ?></a>
						</p>
						<p class="pull-right sr-date">
							<?php 
								Yii::$app->formatter->locale = 'ru-RU';
								echo Yii::$app->formatter->asDate($article['date_public'], 'long');
							?>
						</p>
					</div>
					<div class="sr-content">
						<h4 class="sr-title2"><?= $article['title2']; ?></h4>
						<p class="sr-description"><?= $article['description']; ?></p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="row">
			<a href="<?= Url::to(['/main/more']) ?>" class="btn btn-block btn-default more-articles-button">Больше статей</a>
		</div>
	</div>

</div>

<div class="col-md-3 last-comments-widget">
	<?= 
		LastComments::widget([
			'autoupdate' => 60,
		]);
	?>
</div>

