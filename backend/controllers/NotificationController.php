<?php

namespace backend\controllers;

use backend\models\Conversation;
use backend\models\Notification;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class NotificationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['undo-read', 'close-conversation', 'reopen-conversation'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->role === 'admin';
                        },
                    ],
                    [
                        'actions' => ['index', 'send', 'reply', 'view', 'view-conversation'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $receivedNotifications = Notification::find()
            ->where(['receiver_id' => Yii::$app->user->getId()])
            ->orderBy('created_at DESC')
            ->all();
        $sentNotifications = Notification::find()
            ->where(['sender_id' => Yii::$app->user->getId()])
            ->orderBy('created_at DESC')
            ->all();

        $dataProviderReceived = new ArrayDataProvider([
           'allModels' => $receivedNotifications,
           'pagination' => [
               'pageParam' => 'rcvNt',
               'pageSize' => 5,
           ]
        ]);

        $dataProviderSent = new ArrayDataProvider([
           'allModels' => $sentNotifications,
           'pagination' => [
               'pageParam' => 'sntNt',
               'pageSize' => 5,
           ]
        ]);

        return $this->render('index', [
            'dataProviderReceived' => $dataProviderReceived,
            'dataProviderSent' => $dataProviderSent,
        ]);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws Exception
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionReply($id)
    {
        $originalNotification = $this->findModel($id);
        $conversation = $originalNotification->getConversation()->one();

        if ($conversation->getStatus() === Conversation::CONVERSATION_STATUS_CLOSED) {
            Yii::$app->session->setFlash('danger', 'La conversación está cerrada y no se puede responder');
            return $this->goHome();
        }

        $model = new Notification();
        $model->setReceiver($originalNotification->getSender()->one());

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->sender_id = Yii::$app->user->identity->getId();

                if ($this->request->post('checkboxCloseConversation') == 1) {
                    $conversation->setStatus(Conversation::CONVERSATION_STATUS_CLOSED);
                }
                if (!$conversation->save()) {
                    Yii::$app->session->setFlash('danger', 'Error al guardar la conversación');
                    return $this->render('reply', [
                        'originalNotification' => $originalNotification,
                        'conversation' => $conversation,
                        'model' => $model,
                    ]);
                }
                $model->conversation = $conversation->id;

                if ($model->save()) {
                    if (!$conversation->addNotification($model)) {
                        $model->delete();
                        Yii::$app->session->setFlash('danger', 'Error al responder la notificación');
                        return $this->render('reply', [
                            'originalNotification' => $originalNotification,
                            'conversation' => $conversation,
                            'model' => $model,
                        ]);
                    }
                    if (!$conversation->save()) {
                        $model->delete();
                        Yii::$app->session->setFlash('danger', 'Error al guardar la conversación');
                        return $this->render('reply', [
                            'originalNotification' => $originalNotification,
                            'conversation' => $conversation,
                            'model' => $model,
                        ]);
                    }
                    $originalNotification->setStatus(Notification::MESSAGE_STATUS_REPLIED);
                    $originalNotification->setResponseId($model->getId());
                    if (!$originalNotification->save()) {
                        Yii::$app->session->setFlash('danger', 'Error al marcar la notificación original como respondida');
                        // TODO: manage this case
                    }
                    Yii::$app->session->setFlash('success', 'Notificación de respuesta enviada con éxito');
                    return $this->redirect('index');
                } else {
                    Yii::$app->session->setFlash('danger', 'Error al guardar el mensaje');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('reply', [
            'originalNotification' => $originalNotification,
            'conversation' => $conversation,
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $model = $this->findModel($id);

        $userId = Yii::$app->user->identity->getId();

        if ($userId != $model->sender_id && $userId != $model->receiver_id) {
            throw new ForbiddenHttpException('No tienes permiso para acceder a esta página');
        }
        if ($model->status === $model::MESSAGE_STATUS_UNREAD && $model->receiver_id == Yii::$app->user->getId()) {
            $model->setStatus($model::MESSAGE_STATUS_READ);
            $model->save();
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionUndoRead($id): Response
    {
        $model = $this->findModel($id);
        $model->setStatus($model::MESSAGE_STATUS_UNREAD);
        $conversation = $model->getConversation()->one();
        if ($conversation->getStatus() == Conversation::CONVERSATION_STATUS_CLOSED) {
            $conversation->openConversation();
            $conversation->save();
        }
        $model->save();
        return $this->redirect('index');
    }

    /**
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionViewConversation($id): string
    {
        $model = Conversation::find()->where(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
        $firstNotification = $this->findModel($model->first_notification);
        $sender = $firstNotification->getSender()->one();
        $receiver = $firstNotification->getReceiver()->one();

        $userId = Yii::$app->user->identity->getId();

        if ($userId != $sender->getId() && $userId != $receiver->getId()) {
            throw new ForbiddenHttpException('No tienes permiso para acceder a esta página');
        }

        $notifications = $model->getNotifications()->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $notifications,
            'pagination' => [
                'pageSize' => 5,
            ]
        ]);

        return $this->render('view-conversation', [
            'model' => $model,
            'sender' => $sender,
            'receiver' => $receiver,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionCloseConversation($id): string
    {
        $model = Conversation::find()->where(['id' => $id])->one();
        $model->closeConversation();
        return $this->openViewConversation($model);
    }

    /**
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionReopenConversation($id): string
    {
        $model = Conversation::find()->where(['id' => $id])->one();
        $model->openConversation();
        return $this->openViewConversation($model);
    }

    /**
     * @throws Exception
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionSend()
    {
        $model = new Notification();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->sender_id = Yii::$app->user->identity->getId();

                $conversation = new Conversation();
                $conversation->setStatus(Conversation::CONVERSATION_STATUS_OPEN);
                if (Yii::$app->user->identity->role === 'admin') {
                    $conversation->setClientId($model->receiver_id);
                } else {
                    $conversation->setClientId(Yii::$app->user->identity->getId());
                }
                if (!$conversation->save()) {
                    Yii::$app->session->setFlash('danger', 'Error al guardar la nueva conversación');
                    return $this->render('send', [
                        'model' => $model,
                    ]);
                }
                $model->conversation = $conversation->id;

                if ($model->save()) {
                    if (!$conversation->addNotification($model)) {
                        $model->delete();
                        $conversation->delete();
                        Yii::$app->session->setFlash('danger', 'Error al crear el mensaje');
                        return $this->render('send', [
                            'model' => $model,
                        ]);
                    }
                    return $this->redirect('index');
                } else {
                    $conversation->delete();
                    Yii::$app->session->setFlash('danger', 'Error al guardar el mensaje');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('send', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected
    function findModel(int $id): Notification
    {
        if (($model = Notification::findOne(['id' => $id])) !== null) {
            $conversation = $model->getConversation()->one();
            $model->conversation = $conversation ? $conversation->id : 0;
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }

    /**
     * @param $model
     * @return string
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    private function openViewConversation($model): string
    {
        $model->save();
        $firstNotification = $this->findModel($model->first_notification);
        $sender = $firstNotification->getSender()->one();
        $receiver = $firstNotification->getReceiver()->one();
        $notifications = $model->getNotifications()->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $notifications,
            'pagination' => [
                'pageSize' => 5,
            ]
        ]);

        return $this->render('view-conversation', [
            'model' => $model,
            'sender' => $sender,
            'receiver' => $receiver,
            'dataProvider' => $dataProvider,
        ]);
    }
}
