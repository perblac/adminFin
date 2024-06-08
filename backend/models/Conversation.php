<?php

namespace backend\models;

use common\models\User;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "conversation".
 *
 * @property int $id
 * @property int $client_id
 * @property string $status
 * @property int $first_notification
 * @property int|null $created_at
 * @property int $created_by
 * @property int|null $updated_at
 * @property int $updated_by
 *
 * @property User $client
 * @property User $createdBy
 * @property Notification $firstNotification
 * @property Notification[] $notifications
 */
class Conversation extends ActiveRecord
{
    const CONVERSATION_STATUS_OPEN = 'open';
    const CONVERSATION_STATUS_CLOSED = 'closed';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'conversation';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['client_id'], 'required'],
            [['client_id', 'created_at', 'created_by', 'updated_at', 'first_notification'], 'integer'],
            [['status'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => [self::CONVERSATION_STATUS_OPEN, self::CONVERSATION_STATUS_CLOSED]],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['client_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['first_notification'], 'exist', 'skipOnError' => true, 'targetClass' => Notification::class, 'targetAttribute' => ['first_notification' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'first_notification' => 'First Notification',
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return ActiveQuery
     */
    public function getClient(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'client_id']);
    }

    /**
     * @param int $client_id
     */
    public function setClientId(int $client_id): void
    {
        $this->client_id = $client_id;
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[FirstNotification]].
     *
     * @return ActiveQuery
     */
    public function getFirstNotification(): ActiveQuery
    {
        return $this->hasOne(Notification::class, ['id' => 'first_notification']);
    }

    /**
     * @param int $first_notification_id
     */
    public function setFirstNotification(int $first_notification_id): void
    {
        $this->first_notification = $first_notification_id;
    }

    /**
     * Gets query for [[Notifications]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getNotifications(): ActiveQuery
    {
        return $this->hasMany(Notification::class, ['id' => 'notification_id'])->viaTable('notification_conversation', ['conversation_id' => 'id']);
    }

    /**
     * @throws Exception
     */
    public function addNotification($notification): bool
    {
        $this->link('notifications', $notification);
        if (!$this->first_notification) {
            $this->setFirstNotification($notification->id);
        }
        return $this->save();
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function closeConversation()
    {
        if ($this->status === self::CONVERSATION_STATUS_OPEN) {
            $this->status = self::CONVERSATION_STATUS_CLOSED;
        }
    }

    public function openConversation()
    {
        if ($this->status === self::CONVERSATION_STATUS_CLOSED) {
            $this->status = self::CONVERSATION_STATUS_OPEN;
        }
    }


}
