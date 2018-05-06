<?php 
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php //debug($model); ?> 

<?php if (empty($model)): ?>
	<h1 class="sr-empty">Записей не найдено!</h1>
<?php else: ?>
	<h2 class="search-header"><?= $search_header ?></h2>
<?php endif; ?>

<?php foreach ($model as $article): ?>
	<div class="row search-result">
		<div class="col-md-3 sr-image">
			<?php if ($article['image']): ?>
				<img src="<?= Yii::getAlias('@uploads/') . 'SMALL_' . $article['image']; ?>" class="search-result-image">
			<?php endif; ?>
		</div>
		<div class="col-md-9 sr-article-info">
			<div class="sr-header clearfix">
				<a href="<?= Url::to(['/main/single', 'id' => $article['id']]); ?>"><h3 class="sr-title"><?= $article['title']; ?></h3></a>
				<p class="pull-right sr-comment">
					<span class="glyphicon glyphicon-comment sr-comment-icon"></span>
					 <?= $article['comment_count'] ?>
				</p>
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
		<div class="sr-tags pull-right">
			<?php foreach ($article['tags'] as $tag): ?>
				<a href="<?= Url::to(['/main/tag', 'name' => $tag['name']]) ?>">
					<span class="label label-default"><?= $tag['name'] ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
<?php endforeach; ?>

<div id="paginator">
	<?= LinkPager::widget([
	    'pagination' => $pages,
	    'activePageCssClass' => 'srp-active',
	    'disableCurrentPageButton' => true,
	    'disabledPageCssClass' => 'srp-disabled',
	    'nextPageCssClass' => 'srp-next-prev-page',
	    'prevPageCssClass' => 'srp-next-prev-page',
	    'hideOnSinglePage' => true,
	    'maxButtonCount' => 7,
	    'pageCssClass' => 'srp-page',
	]); ?>
</div>