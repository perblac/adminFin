<?php

namespace backend\controllers;

use backend\models\Conversation;
use backend\models\Notification;
use backend\models\NotificationQuery;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * NotificationQueryController implements the CRUD actions for Notification model.
 */
class NotificationQueryController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'update', 'create' , 'delete'],
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->user->identity->role === 'admin';
                            },
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Notification models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new NotificationQuery();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notification model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Notification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Throwable
     * @throws Exception
     * @throws StaleObjectException
     */
    public function actionCreate()
    {
        $model = new Notification();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->conversation == 0) {
                    $conversation = new Conversation();
                    $conversation->setStatus(Conversation::CONVERSATION_STATUS_OPEN);
                    $conversation->setClientId($model->receiver_id);

                    if (!$conversation->save()) {
                        Yii::$app->session->setFlash('danger', 'Error al guardar la nueva conversación');
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                } else {
                    $conversation = Conversation::findOne($model->conversation);
                }

                if ($model->save()) {
                    if (!$conversation->addNotification($model)) {
                        $model->delete();
                        $conversation->delete();
                        Yii::$app->session->setFlash('danger', 'Error al crear el mensaje');
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    } else {
                        $model->conversation = $conversation->id;
                        $model->save();
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('danger', 'Error al guardar el mensaje');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Notification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     * @throws Exception
     * @throws Throwable
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $originalConversation = $model->conversation;

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->conversation == 0) {
                $conversation = new Conversation();
                $conversation->setStatus(Conversation::CONVERSATION_STATUS_OPEN);
                $conversation->setClientId($model->receiver_id);

                if (!$conversation->save()) {
                    Yii::$app->session->setFlash('danger', 'Error al guardar la nueva conversación');
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }

            if ($model->save()) {
                if ($model->conversation == 0) {
                    if (!$conversation->addNotification($model)) {
                        $model->conversation = $originalConversation;
                        $conversation->delete();
                        Yii::$app->session->setFlash('danger', 'Error al crear el mensaje en una nueva conversación');
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    } else {
                        $model->conversation = $conversation->id;
                        $model->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('danger', 'Error al guardar el mensaje');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Notification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException
     * @throws Throwable
     */
    public
    function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
