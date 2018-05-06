<?php foreach ($comments as $comment): ?>
	<div class="comment row" data-id="<?= $comment->id ?>">
		<a name="<?= $comment->id ?>"></a>
		<div class="col-md-1 userpic-name">
			<?php if ($comment->user->userpic != false): ?>
                <?php if (file_exists(Yii::getAlias("@uploads/USERS/small_") . $comment->user->userpic)): ?>
                  <img class="comment-userpic" src="<?= Yii::getAlias("@uploads/USERS/small_") . $comment->user->userpic ?>">
                <?php else: ?>
                  <img class="comment-userpic" src="<?= Yii::getAlias("@uploads/USERS/") . $comment->user->userpic ?>">
                <?php endif ?>
            <?php else: ?>
            	<img class="comment-userpic" src="<?= Yii::getAlias('@uploads') . '/USERS//' . 'placeholder.jpg' ?>">
			<?php endif; ?>
			<p class="name"><?= $comment->user->username ?></p>
		</div>
		<div class="col-md-11 text-comment-wrapper">
			<?php if(!$comment->deleted): ?>
				<p class="comment-text"><?= $comment->text ?></p>
				<?php if ($comment->all_images != NULL): ?>
					<div class="commentImages">
						<?php foreach ($comment->all_images as $image): ?>
							<img class="comment-image" data-toggle="modal" data-target="#imageModal" src="<?= Yii::getAlias("@uploads/") . $image ?>">
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			<?php else: ?>
				<p class="comment-text del-comment">Комментарий был удален!</p>
			<?php endif; ?>
		</div>

		<?php if(!$comment->deleted): ?>
			<button class="respond">Ответить</button>
		<?php endif; ?>
	</div>
<?php endforeach; ?>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
  	<div class="modal-body">
    	<div class="modal-content">
    	</div>
    </div>
  </div>
</div>
