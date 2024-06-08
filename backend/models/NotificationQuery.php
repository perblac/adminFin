<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NotificationQuery represents the model behind the search form of `backend\models\Notification`.
 */
class NotificationQuery extends Notification
{
    public $sender;
    public $receiver;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sender_id', 'receiver_id', 'type', 'created_at', 'conversation'], 'integer'],
            [['message', 'status', 'sender', 'receiver', 'title'], 'safe'],
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
        $query = Notification::find();

        // add conditions that should always apply here
        $query->joinWith(['sender s', 'receiver r', 'conversation']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['sender'] = [
            'asc' => ['s.username' => SORT_ASC],
            'desc' => ['s.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['receiver'] = [
            'asc' => ['r.username' => SORT_ASC],
            'desc' => ['r.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['typeValue'] = [
            'asc' => ['notification.type' => SORT_ASC],
            'desc' => ['notification.type' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['conversation'] = [
            'asc' => ['conversation.id' => SORT_ASC],
            'desc' => ['conversation.id' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
//            'sender_id' => $this->sender_id,
//            'receiver_id' => $this->receiver_id,
            'type' => $this->type,
//            'type' => $this->getTypeKey($this->getTypeValue($this->type)),
//            'created_at' => $this->created_at,
            'notification.status' => $this->status,
            'conversation.id' => $this->conversation,
        ]);

        $query->andFilterWhere(['like', 's.username', $this->sender])
            ->andFilterWhere(['like', 'r.username', $this->receiver])
            ->andFilterWhere(['like', 'message', $this->message]);
//            ->andFilterWhere(['like', 'notification.status', $this->status]);

        return $dataProvider;
    }
}
