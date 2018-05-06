<li>
	<li class="dropdown">
		<a href="" class="dropdown-toggle category-item" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $category['name'] ?>
		<?php if (isset($category['childs'])): ?>
			<span class="caret"></span>
		<?php endif; ?>
		</a>
	<?php if (isset($category['childs'])): ?>
		<ul class="dropdown-menu">
			<?= $this->getMenuHtml($category['childs']); ?>
			<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">All</a></li>
		</ul>
	</li>
	<?php endif; ?>
</li>


