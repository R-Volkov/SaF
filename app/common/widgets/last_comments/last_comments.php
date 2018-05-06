<?php 
use yii\helpers\Url;
use yii\helpers\Html;
?>

<?php $this->getView()->registerCssFile(Yii::getAlias('@web/css/last-comments.css'), $options = [], $key = null) ?>
<?php $this->getView()->registerJsFile(Yii::getAlias('@web/js/last-comments.js'),
		 $options = ['depends' => [frontend\assets\AppAsset::className()],], $key = null) ?>
<?php $this->getView()->registerJs('var last_comment_widget_config =' . $this->config . ';', \yii\web\View::POS_HEAD) ?>

<div class="last-comments">
	<h2>Последние комментарии:</h2>
	<?php foreach ($comments as $comment): ?>
		<?php unset($comment['article']) ?>
		<?php unset($comment['user']) ?>
		<div class="one-comment">
			<div class="head">
				<p class="username-userpic">
				<?php if ($comment['userpic'] != false): ?>
	                <?php if (file_exists(Yii::getAlias("@uploads/USERS/small_") . $comment['userpic'])): ?>
	                  <img src="<?= Yii::getAlias("@uploads/USERS/small_") . $comment['userpic'] ?>">
	                <?php else: ?>
	                  <img src="<?= Yii::getAlias("@uploads/USERS/") . $comment['userpic'] ?>">
	                <?php endif ?>
	            <?php else: ?>
	            	<img src="<?= Yii::getAlias('@uploads') . '/USERS//' . 'placeholder.jpg' ?>">
				<?php endif; ?>
					<span class="author"><?= $comment['username']; ?></span>
				</p>
				<span class="article-name">К статье: <a href="<?= Url::to(['/main/single', 'id' => $comment['article_id']]) ?>#<?= $comment['id'] ?>"><h3><?= $comment['article_title'] ?></h3></a></span>
			</div>
			<div class="text"><?= $comment['text'] ?></div>
			<span class="how-time-ago">
				<?= 
					mb_strtoupper(mb_substr(Yii::$app->formatter->asRelativeTime($comment['created_at']), 0, 1)) 
					. mb_substr(Yii::$app->formatter->asRelativeTime($comment['created_at']), 1); 
				?>
			</span>
		</div>
	<?php endforeach; ?>
</div>

