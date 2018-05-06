<?php 
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use common\models\Articles;
use yii\helpers\HtmlPurifier;


class Comments extends ActiveRecord {

	public $ifiles;
	public $article_title;
	public $username;
	public $userpic;
	private $raw_images = [];
	public $all_images = NULL;

	public static function tableName() {
		return '{{comments}}';
	}

	public function getUser(){
    	return $this->hasOne(MyUser::className(), ['id' => 'user_id']);
    }

    public function getArticle(){
    	return $this->hasOne(Articles::className(), ['id' => 'article_id']);
    }

	public function rules() {
		return [
			[['text', 'user_id', 'article_title', 'images', 'username'], 'safe'],
			[['article_id'], 'safe'],
			[['ifiles'], 'file', 'maxFiles' => 4],
		];
	}

	public function getAnswers() {
		if ($this->addressees != false) {
			return (json_decode($this->addressees));
		} else {
			return NULL;
		}
	}

	public function getAutorName()
    {
        return $this->user->username;
    }

	public function afterFind() {
		if ($this->images != false) {
			//$this->all_images = json_decode($this->images);
			$this->all_images = explode('###', $this->images);
		}
		return true;
	}

	protected function getRightText($text)
	{

		$this->text = HtmlPurifier::process($this->text, [
    		'AutoFormat.Linkify' => true,
    		'AutoFormat.AutoParagraph' => true,
    		'HTML.AllowedElements' => [
    			'span' => true,
    			'a' => true,
    			'p' => true,
    		],
		]);

		$pattern = '/>>(\d+)-([\w\_-]+),/';
		$replacement = '<span class="answer" data-respond="$1"><a class="anchor" href="#$1">@$2</a></span>';
		$this->text = preg_replace($pattern, $replacement, $text);

		//$this->text = $this->linkNormalaizer($this->text);
		$this->text = nl2br($this->text);

		return true;
	}

	protected function linkNormalaizer($text)
	{
		$pattern = '/((http|www)[^\s]+)/';
		$replacement = '<a href="$1" target="_blank">$1</a>';
		$text = preg_replace($pattern, $replacement, $text);
		return $text;		
	}

	public function beforeSave($insert) {

		if (!$this->isNewRecord) return true;

		$this->getRightText($this->text);

		if ($this->validate()) { 
			if ($this->ifiles != false) {
				foreach ($this->ifiles as $file) {
					$image_name = time() . '_' . Yii::$app->getSecurity()->generateRandomString(8) . $file->baseName . '.' . $file->extension;
            		$this->raw_images[] = $image_name;
                	$file->saveAs(Yii::getAlias("@absolute_uploads/") . $image_name); 
            	}
			}
            if (count($this->raw_images)) {
            	//$this->images = json_encode($this->raw_images);
            	$this->images = implode('###', $this->raw_images);
            } else {
            	$this->images = NULL;
            }
            return true;
        } else {
            return false;
        }
	}


}