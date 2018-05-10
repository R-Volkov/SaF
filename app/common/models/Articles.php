<?php
namespace common\models;

use yii\db\ActiveRecord;
use Yii;
use yii\base\Event;
use common\models\Categories;
use common\models\Comments;
use common\models\MyUser;
use common\models\Tag;
use common\models\ArticlesTags;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;


class Articles extends ActiveRecord {

	public $image_file = NULL;
    //public $path_image_folder;
    public $absolute_uploads_path;
    public $tags_array;
    public $comment_count;
    public $category_name;

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'alias',
                'ensureUnique' => true,
            ],
        ];
    }

    public static function tableName()
    {
    	return '{{articles}}';
    }

    public function getCategory()
    {
    	return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }

    public function getUser()
    {
        return $this->hasOne(MyUser::className(), ['id' => 'user_id']);
    }

    public function getComment()
    {
        return $this->hasMany(Comments::className(), ['article_id' => 'id']);
    }

    public function getArticlesTags()
    {
        return $this->hasMany(ArticlesTags::className(), ['article_id' => 'id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('articles_tags', ['article_id' => 'id']); 
    }

    public function rules()
    {
        return [
            [['title'], 'required', 'message' => 'Поле обязательно для заполнения!'],
            [['title2', 'description', 'image', 'body'], 'default', 'value' => NULL],
            [['date_public'], 'default', 'value' => date('o-m-d H:i:s')],
            [['comments', 'exhibit'], 'default', 'value' => true],
            [['important', 'inwork'], 'default', 'value' => false],
            [['image_file'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['title'], 'unique'],
            [['title'], 'trim'],
            [['category_id'], 'integer'],
            [['tags_array', 'tags', 'user_id'], 'safe'],
        ];
    }

    public function afterFind()
    {
        $this->tags_array = $this->tags;
	}

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->saveTags();
    }

    protected function saveTags()
    {
        //Выбираем все имена тегов. 
        $all_tags = ArrayHelper::map(Tag::find()->all(), 'id', 'name');

        //Ищем расхождение между добавленными тегами и всеми тегами. Результат расхождения - теги, которых нет в таблице тегов. Добавляем их в таблицу.
        if ($this->tags_array != false) {
            $new_tags = array_diff_key(array_flip($this->tags_array), $all_tags);
            if (!empty($new_tags)) {
                foreach ($new_tags as $name => $id) {
                    $new_tag = new Tag();
                    $new_tag->name = $name;
                    $new_tag->save();
                    //Удаляем новый тег, потому что он представлен строковым значением (названием), а не айди
                    unset($this->tags_array[array_search($name, $this->tags_array)]);
                    //Добавляем айди нового, уже добавленного в таблицу тегов тега
                    $this->tags_array[] = $new_tag->id;
                }            
            }
        }

        //Создаем массив с айди тегов, которые были в записи изначально
        $old_tags = ArrayHelper::map($this->tags, 'id', 'id');

        //Перебираем новые теги и если в новых тегах есть тег, которого нет в массиве старых тегов - сохраняем его в таблицу связи запись-тег
        if ($this->tags_array != false) {
            foreach ($this->tags_array as $tag) {
                if (!in_array($tag, $old_tags)) {
                    $model = new ArticlesTags();
                    $model->article_id = $this->id;
                    $model->tag_id = $tag;
                    $model->save();
                }
                //Удаляем все теги которые были одновременно среди старых и новых тегов записи. Так остаются старые неиспользуемые теги, которые нужно удалить
                if (isset($old_tags[$tag])) {
                    unset($old_tags[$tag]);
                }
            }            
        }

        //Удаляем старые ненужные теги
        $old_articletag_relations = (new ArticlesTags())->find()->where(['IN', 'tag_id', $old_tags])->andWhere(['article_id' => $this->id])->all();
        foreach ($old_articletag_relations as $relation) {
            $relation->delete();
        }
    }

}