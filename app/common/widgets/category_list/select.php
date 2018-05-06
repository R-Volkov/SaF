<option value="<?= $category['id'] ?>"
	<?php 
		if ($category['id'] == $this->field_value) echo ' selected'; 
		if (isset($category['childs'])) echo ' disabled' 
	?>>
<?= $indent . $category['name'] ?></option>
<?php if (isset($category['childs'])): ?>
	<ul>
		<?= $this->getMenuHtml($category['childs'], $indent . '^__') ?>
	</ul>
<?php endif; ?>