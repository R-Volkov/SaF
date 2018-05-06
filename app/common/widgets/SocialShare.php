<?php 
namespace common\widgets;

use yii\base\Widget;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class SocialShare extends Widget {

	public $networks = [];

	public $title;
	public $description;
	public $url;
	public $image_url;
	public $type;
	public $site_name;
	public $via_twitter;
	public $hashtags;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$html = '';
		if (!empty($this->networks)) {
			foreach ($this->networks as $network) {
				$html .= $this->$network();
			}
		}
		ob_start();
		include __DIR__. "/social-share/social.php";
		return ob_get_clean();
	}

	protected function twitter()
	{
		return '<div class="social_share twitter">' . Html::a('Tweet', Url::to([
				'https://twitter.com/share', 
				'ref_src' => 'twsrc%5Etfw', 
				'via' => $this->via_twitter ?? '',
			], true), [
				'class' => 'twitter-share-button',
				'data-size' => 'large',
				'data-show-count' => true,
			]) . '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>' . '</div>';
	}
}