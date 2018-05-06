<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
//use common\models\ArticlesTags;
use common\models\Articles;

class Tag extends ActiveRecord
{
	public static function tableName()
	{
		return '{{tags}}';
	}

	public function rules()
	{
		return [
			[['id', 'name'], 'safe'],
		];
	}

	public function getArticlesTags()
    {
        return $this->hasMany(ArticlesTags::className(), ['tag_id' => 'id']);
    }

    public function getArticles()
    {
        return $this->hasMany(Articles::className(), ['id' => 'article_id'])->viaTable('articles_tags', ['tag_id' => 'id']);
    }
} 