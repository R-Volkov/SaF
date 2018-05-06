<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comments;

/**
 * ArticlesSearch represents the model behind the search form about `backend\models\Articles`.
 */
class Moderation extends Comments
{

    public $from_date;
    public $to_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'from_date', 'to_date', 'article_id', 'text'], 'safe'],
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


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Comments::find()->with(['article'])->joinWith(['user'])->distinct();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
                'attributes' => [
                    'id',
                    'created_at',
                    'user_id' => [
                        'desc' => ['{{%users}}.username' => SORT_DESC],
                        'asc' => ['{{%users}}.username' => SORT_ASC],
                    ],
                ]
            ],
        ]);

        // $dataProvider->setSort([
        //     // 'attributes' => [
        //     //     'id',
        //     //     'created_at',
        //     //     'user_id' => [
        //     //         'desc' => ['{{%users}}.username' => SORT_DESC],
        //     //         'asc' => ['{{%users}}.username' => SORT_ASC],
        //     //     ],
        //     // ]
        // ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'text' => $this->text,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'article_id', $this->article_id])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['>=', 'created_at', $this->from_date ? $this->from_date . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'created_at', $this->to_date ? $this->to_date . ' 23:59:59' : null]);

        return $dataProvider;
    }
}