<?php 
namespace common\widgets;

use yii\base\Widget;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Slider extends Widget {

	public $articles_array;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		ob_start();
		include __DIR__. "/slider/slider.php";
		return ob_get_clean();
	}

}