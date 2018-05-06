<?php 
namespace common\widgets;

use yii\base\Widget;
use Yii;
use common\models\Comments;

class LastComments extends Widget {

	public $autoupdate;
	protected $count;
	protected $config;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$comments = $this->getComments();
		$this->config = $this->setConfig();
		ob_start();
		include __DIR__. "/last_comments/last_comments.php";
		return ob_get_clean();
	}

	protected function getComments()
	{
		$this->count = Comments::find()->where(['>', 'id', 0])->count();
		$comments = Comments::find()
			->select(['comments.*', 'users.username AS username', 'articles.title AS article_title', 'users.userpic AS userpic'])
			->where(['deleted' => 0])
			->joinWith(['user', 'article'])
			->orderBy(['created_at' => SORT_DESC])
			->asArray()
			->limit(25)
			->all();
		return $comments;
	}

	protected function setConfig()
	{
		return json_encode([
				"autoupdate" => $this->autoupdate, 
				"count" => $this->count,
			]);
	}

}