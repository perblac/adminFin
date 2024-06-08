<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserQuery represents the model behind the search form of `common\models\User`.
 */
class UserQuery extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'status'], 'integer'],
            [['address'], 'string'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'role', 'status', 'fullname', 'address', 'phone1', 'phone2'], 'safe'],
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
    public function search(array $params): ActiveDataProvider
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['user.role' => SORT_DESC, 'user.status' => SORT_ASC],
            'desc' => ['user.role' => SORT_DESC, 'user.status' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
//            'id' => $this->id,
            'status' => $this->status,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
//            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
//            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
//            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
//            ->andFilterWhere(['like', 'verification_token', $this->verification_token])
            ->andFilterWhere(['like', 'role', $this->role])
//            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'address', $this->address])
//            ->andFilterWhere(['like', 'phone1', $this->phone1])
//            ->andFilterWhere(['like', 'phone2', $this->phone2])
            ;

        return $dataProvider;
    }
}
