<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Articles;
use common\models\Tag;
use yii\db\Query;

/**
 * ArticlesSearch represents the model behind the search form about `backend\models\Articles`.
 */
class ArticlesSearch extends Articles
{

    //public $tag_id;
    public $tags;
    public $from_date;
    public $to_date;
    public $published;
    public $author;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'comments', 'important', 'exhibit', 'inwork'], 'integer'],
            [['doc_date', 'title', 'title2', 'description', 'image', 'body', 'tags', 'date_public', 'autorName', 'tag_id', 'comment_count', 'published', 'user_id', 'author'], 'safe'],
            [['from_date', 'to_date'], 'date', 'format' => 'yyyy-mm-dd'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    protected function tagsId($tags)
    {
        if ($tags == false) {
            return [];
        }
        $tags_array = explode("; ", $tags);
        $tags_id_array = [];
        $tags = Tag::find()->where(['IN', 'name', $tags_array])->all();
        foreach ($tags as $tag) {
            $tags_id_array[] = $tag->id;
        }
        if (empty($tags_id_array)) {
            $tags_id_array[0] = 0;
        }
        return $tags_id_array;
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $sub_query = (new Query())->select('COUNT(*)')
            ->from('comments')
            ->where('comments.article_id = {{articles}}.id');

        $query = Articles::find()
            ->select(['{{%articles}}.*', 'comment_count' => $sub_query,])
            ->joinWith(['comment'], false)
            ->groupBy('{{%articles}}.id')
            ->with(['category', 'user', 'tags'])
            ->joinWith(['articlesTags'], false)
            ->distinct();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_public' => SORT_DESC,
                ],
                'attributes' => [
                    'id',
                    'date_public',
                    'comment_count',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'comments' => $this->comments,
            'important' => $this->important,
            'exhibit' => $this->exhibit,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'title2', $this->title2])
            //->andFilterWhere(['like', 'author', $this->autor])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['>=', 'date_public', $this->from_date ? $this->from_date . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'date_public', $this->to_date ? $this->to_date . ' 23:59:59' : null])
            ->andFilterWhere(['IN', '{{articles_tags}}.tag_id', $this->tagsId(trim($this->tags, '; '))]);

        if ($this->published == 'waiting') {
            $query->andFilterWhere(['>', 'date_public', date('Y-m-d H:i:s')])
                ->orFilterWhere(['inwork' => true]);

        }

        if ($this->published == 'published') {
            $query->andFilterWhere(['<', 'date_public', date('Y-m-d H:i:s')])
                ->andFilterWhere(['inwork' => false]);

        }

        return $dataProvider;
    }
}