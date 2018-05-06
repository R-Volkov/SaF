<?php
use yii\helpers\Url;
?>

<?php foreach ($model as $mod): ?>


<h1><a href="<?= Yii::$app->frontendUrlManager->createUrl(['main/single', 'id' => $mod['id']] ); ?>" target='_blank'><?=$mod['title'] ?></a></h1>
<br>
<?= $mod['body'] ?>
<br>
<?=$mod['category_id'] ?> 
<br>
<p><a class="btn btn-primary" href="<?= Url::to(['main/update', 'id' => $mod['id']])?>">Update</a></p>


<?php endforeach; ?>