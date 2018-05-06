<?php 
namespace common\widgets;

use yii\base\Widget;
use common\models\Comments;
use Yii;

class ArticleComments extends Widget {

	public $article_id;
	public $article_title;

	public function init() {
		parent::init();
	}

	public function run() {
		$comments = Comments::find()->where(['article_id' => $this->article_id])->with('user')->all();
		return $this->commentTemplate($comments);
	}

	protected function commentTemplate ($comments) {
		ob_start();
		include __DIR__. "/article_comments/comment.php";
		return ob_get_clean();
	}
 
}