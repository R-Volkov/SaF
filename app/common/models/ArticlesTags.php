<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Articles;
use common\models\Tag;

class ArticlesTags extends ActiveRecord
{
	public static function tableName()
	{
		return '{{articles_tags}}';
	}

	public function rules()
	{
		return [
			[['article_id', 'tag_id'], 'safe'],
		];
	}

	public function getArticle()
    {
        return $this->hasOne(Articles::className(), ['id' => 'article_id']);
    }

    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }

} 