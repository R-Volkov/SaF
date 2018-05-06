<li>
	<a href="#">
		<?php if (!isset($category['childs'])): ?>
			<span><div class="checkbox"><label><input type="checkbox" name="Search[category_id][]" value="<?= $category['id'] ?>"></label></div></span>
		<?php endif; ?>
		<?= $category['name']?>
		<?php if (isset($category['childs'])): ?>
			<span class="caret"></span>
		<?php endif; ?>
	</a>
	<?php if (isset($category['childs'])): ?>
		<ul>
			<?= $this->getMenuHtml($category['childs'])?>
		</ul>
	<?php endif; ?>
</li>