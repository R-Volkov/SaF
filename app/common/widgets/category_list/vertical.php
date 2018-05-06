<?php 
use yii\helpers\Url;
 ?>

<li class="li-vertCat dropdown">
	<a href="<?= Url::to(['/main/category', 'name' => $category['name']]) ?>" class="a-vertCat dropdown-toggle" role="button" >
		<?= $category['name']?>
		<?php if (isset($category['childs'])): ?>
			<span class="caret"></span>
		<?php endif; ?>
	</a>
	<?php if (isset($category['childs'])): ?>
		<ul class="ul-vertCat dropdown-menu">
			<?= $this->getMenuHtml($category['childs'])?>
		</ul>
	<?php endif; ?>
</li>