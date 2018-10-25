<?php

namespace backend\models\app;

use common\models\mysql\AppsDbUsers;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AppsDbUsersSearch represents the model behind the search form of `common\models\mysql\AppsDbUsers`.
 */
class AppsDbUsersSearch extends AppsDbUsers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'owner_id'], 'integer'],
            [['username', 'user_password', 'permissions', 'database', 'timestamp'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = AppsDbUsers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'owner_id' => $this->owner_id,
            'timestamp' => $this->timestamp,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'user_password', $this->user_password])
            ->andFilterWhere(['like', 'permissions', $this->permissions])
            ->andFilterWhere(['like', 'database', $this->database]);

        return $dataProvider;
    }
}
