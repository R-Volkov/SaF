<?php 
use yii\helpers\Url;
?>

<?php $this->getView()->registerCssFile(Yii::getAlias('@web/css/slider.css'), $options = [], $key = null) ?>
<?php $this->getView()->registerJsFile(Yii::getAlias('@web/js/slider.js'), $options = ['depends' => [frontend\assets\AppAsset::className()],], $key = null) ?>

<div class="slider">

	<div class="current-full">
	</div>

	<div class="miniature-col">
		<?php foreach ($this->articles_array as $article): ?>
			<div class="article-miniature">
				<img src="<?= Url::to(['@uploads/' . $article['image']]) ?>">
				<a href="<?= Url::to(['/main/single', 'id' => $article['id']]); ?>"><h1><?= $article['title']; ?></h1></a>
				<p style="display: none;"><?= $article['description']; ?></p>
			</div>
		<?php endforeach; ?>
		<div class="article-miniature" style="display: none;"></div>
	</div>
	
</div>