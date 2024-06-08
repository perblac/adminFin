<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ConversationQuery represents the model behind the search form of `backend\models\Conversation`.
 */
class ConversationQuery extends Conversation
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'client_id', 'created_at', 'created_by', 'first_notification', 'updated_at'], 'integer'],
            [['status'], 'safe'],
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
        $query = Conversation::find();

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
            'client_id' => $this->client_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'first_notification' => $this->first_notification,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
