<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MyUser;

/**
 * ArticlesSearch represents the model behind the search form about `backend\models\Articles`.
 */
class UserSearch extends MyUser
{

    //public $tag_id;
    public $from_date;
    public $to_date;
    public $ban;
    protected $roles;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'username', 'from_date', 'to_date', 'email', 'ban', 'role'], 'safe'],
        ];
    }

    public function __construct($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    protected function banUntil($ban)
    {
        if ($ban == 'ban_until') {
            return date('Y-m-d H:i:s');
        }
    }

    protected function unlimBan($ban)
    {
        if ($ban == 'unlim_ban') {
            return true;
        }
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
        $query = MyUser::find()
            ->select(['{{%users}}.*', 'COUNT(comments.id) AS comment_count'])
            ->joinWith(['comment'], false)
            ->groupBy('{{%users}}.id')
            ->where(['IN', 'role', $this->roles])
            ->distinct();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
                'attributes' => [
                    'id',
                    'created_at',
                    'username',
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
            'unlim_ban' => $this->unlimBan($this->ban),
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['>=', 'ban_until', $this->banUntil($this->ban)])
            ->andFilterWhere(['>=', 'created_at', $this->from_date ? $this->from_date . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'created_at', $this->to_date ? $this->to_date . ' 23:59:59' : null]);

        return $dataProvider;
    }
}