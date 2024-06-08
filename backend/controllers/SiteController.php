<?php

namespace backend\controllers;

use backend\models\Notification;
use common\models\LoginForm;
use backend\models\CreateAccountForm;
use common\models\User;
use backend\models\ResetPasswordForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create-account'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->role === 'admin';
                        },
                    ],
                    [
                        'actions' => ['logout', 'index', 'profile', 'edit-name', 'notifications', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $numberUnreadNotifications = sizeof(Yii::$app->user->identity->getUnreadNotifications());
            if ($numberUnreadNotifications > 0) {
                Yii::$app->session->setFlash('info', 'Tiene '.$numberUnreadNotifications.' notificaci'.($numberUnreadNotifications>1?'ones':'ón').' sin leer. '.Html::a('Ir a notificaciones', 'notification/index'));
            }
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

//        return $this->goHome();
        return  $this->redirect('@siteFront');
    }

    /**
     * Displays profile page.
     *
     * @return Response|string
     * @throws Exception
     */
    public function actionProfile()
    {
        $model = User::findIdentity(Yii::$app->user->identity->getId());
        if ($model->role === 'admin') {
            $model->setScenario('updateAdminProfile');
        } else {
            $model->setScenario('updateProfile');
        }

        if ($model->load(Yii::$app->request->post())  && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Perfil actualizado.');
                return $this->redirect('@siteBack');
            }
            Yii::$app->session->setFlash('danger', 'Hubo un error al actualizar el perfil.');
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Displays notifications page.
     *
     * @return string
     */
    public function actionNotifications(): string
    {
        $receivedNotifications = Notification::find()->where(['receiver_id' => Yii::$app->user->getId()])->all();
        return $this->render('notifications', [
            'receivedNotifications' => $receivedNotifications,
        ]);
    }

    /**
     * Create client account.
     *
     * @return Response|string
     */
    public function actionCreateAccount()
    {
        $model = new CreateAccountForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Usuario registrado. Correo electrónico de verificación enviado.');
            return $this->redirect(['site/index']);
        }

        return $this->render('createAccount', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return Response
     */
    public function actionRequestPasswordReset(): Response
    {
        if ($this->sendEmailResetPassword()) {
            Yii::$app->session->setFlash('success', 'Consulte su correo electrónico para cambiar su contraseña.');

            return $this->redirect('index');
        }

        Yii::$app->session->setFlash('error', 'Lo sentimos, hubo un error al mandar el email a su dirección de correo electrónico proporcionada..');

        return $this->redirect('profile');
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return Response|string
     * @throws BadRequestHttpException
     */
    public function actionResetPassword(string $token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Nueva contraseña guardada.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was sent
     * @throws Exception
     */
    private function sendEmailResetPassword(): bool
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Restablecimiento de contraseña para ' . Yii::$app->name)
            ->send();
    }
}
