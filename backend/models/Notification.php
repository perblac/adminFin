<?php

namespace backend\models;

use common\models\User;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property string|null $title
 * @property string $message
 * @property int $type
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $status
 * @property int $response_id
 * @property int|null $created_at
 * @property integer $updated_at
 * @property int $updated_by
 *
 * @property int $conversation
 * @property User $receiver
 * @property User $sender
 */
class Notification extends ActiveRecord
{
    const MESSAGE_TYPE_A = 1;
    const MESSAGE_TYPE_B = 2;
    const MESSAGE_TYPE_C = 3;

    const MESSAGE_STATUS_UNREAD = 'unread';
    const MESSAGE_STATUS_READ = 'read';
    const MESSAGE_STATUS_REPLIED = 'replied';

    public $conversation;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'notification';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'sender_id',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'message'], 'trim'],
            [['title', 'sender_id', 'receiver_id', 'type', 'conversation'], 'required'],
            [['sender_id', 'receiver_id', 'type', 'created_at', 'conversation', 'response_id'], 'integer'],
            ['sender_id', 'compare', 'compareAttribute' => 'receiver_id', 'operator' => '!==', 'type' => 'number'],
            ['receiver_id', 'compare', 'compareAttribute' => 'sender_id', 'operator' => '!==', 'type' => 'number'],
            [['message'], 'string'],
            [['status', 'title'], 'string', 'max' => 255],
            [['status'], 'in', 'range' =>[self::MESSAGE_STATUS_UNREAD, self::MESSAGE_STATUS_READ, self::MESSAGE_STATUS_REPLIED]],
            [['type'], 'in', 'range' => [self::MESSAGE_TYPE_A, self::MESSAGE_TYPE_B, self::MESSAGE_TYPE_C]],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['receiver_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Emisor',
            'receiver_id' => 'Receptor',
            'message' => 'Mensaje',
            'type' => 'Tipo',
            'status' => 'Estado',
            'created_at' => 'Creado en',
            'conversation' => 'Conversación',
            'title' => 'Título',
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets query for [[Conversation]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getConversation(): ActiveQuery
    {
        return $this->hasOne(Conversation::class, ['id' => 'conversation_id'])->viaTable('notification_conversation', ['notification_id' => 'id']);
    }

    public function getOptionConversations(): array
    {
        $conversations = Conversation::find()->where(['status' => 'open'])->all();
        return ArrayHelper::map($conversations, 'id', 'id');
    }

    /**
     * @return int
     */
    public function getResponseId(): int
    {
        return $this->response_id;
    }

    /**
     * @param int $response_id
     */
    public function setResponseId(int $response_id): void
    {
        $this->response_id = $response_id;
    }

    /**
     * Gets query for [[Receiver]].
     *
     * @return ActiveQuery
     */
    public function getReceiver(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'receiver_id']);
    }

    /**
     * @param User $receiver
     */
    public function setReceiver(User $receiver): void
    {
        $this->receiver_id = $receiver->getId();
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return ActiveQuery
     */
    public function getSender(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
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

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getOptionUsers($role = null): array
    {
        if ($role) {
            $users = User::find()->where(['status' => 10])->andWhere(['role' => $role])->all();
        } else {
            $users = User::find()->where(['status' => 10])->all();
        }
        return ArrayHelper::map($users, 'id', 'fullname');
    }

    public function getOptionTypes(): array
    {
        return [
            /* Change here message types:
            self::MESSAGE_TYPE_A => 'Tipo A',
            self::MESSAGE_TYPE_B => 'Tipo B',
            self::MESSAGE_TYPE_C => 'Tipo C',
            */
            self::MESSAGE_TYPE_A => 'Aviso',
            self::MESSAGE_TYPE_B => 'Información',
            self::MESSAGE_TYPE_C => 'Petición',
        ];
    }

    public function getTypeValue($type): string
    {
        return $this->getOptionTypes()[$type];
    }

    public function getOptionStatuses(): array
    {
        return [
            self::MESSAGE_STATUS_UNREAD => 'Sin leer',
            self::MESSAGE_STATUS_READ => 'Leído',
            self::MESSAGE_STATUS_REPLIED => 'Respondido',
        ];
    }

    public function getStatusValue($status): string
    {
        return $this->getOptionStatuses()[$status];
    }
}
