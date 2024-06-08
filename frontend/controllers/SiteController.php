<?php

namespace frontend\controllers;

use frontend\models\BudgetForm;
use frontend\models\VerifyEmailForm;
use IntlDateFormatter;
use kartik\mpdf\Pdf;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\captcha\CaptchaAction;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ContactForm;
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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $numberUnreadNotifications = sizeof(Yii::$app->user->identity->getUnreadNotifications());
            if ($numberUnreadNotifications > 0) {
                Yii::$app->session->setFlash('info', 'Tiene ' . $numberUnreadNotifications . ' notificaci' . ($numberUnreadNotifications > 1 ? 'ones' : 'ón') . ' sin leer. ' . Html::a('Ir a notificaciones', 'notification/index'));
            }
            return $this->redirect('@siteBack');
        }
        // clear password field
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContacto()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Gracias por ponerse en contacto con nosotros. Le responderemos lo antes posible.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al enviar su mensaje.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays budget form page.
     *
     * @return mixed
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionPresupuesto()
    {
        $model = new BudgetForm();

        if (isset(Yii::$app->request->post('BudgetForm')['budgetContact'])) {
            if (Yii::$app->request->post('BudgetForm')['budgetContact'] == 'true') {
                $model->setScenario('budgetContact');
            } else {
                $model->setScenario('default');
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->contractedServices = Yii::$app->request->post('BudgetForm')['contractedServices'];
            if ($model->contractedServices === "") $model->contractedServices = array();
            if (empty($model->totalHouseholds)) $model->totalHouseholds = $model->floors * $model->floorHouseholds;
            $model->budgetTotal = $this->calculateBudget($model);

            if ($model->validate()) {
                if ($model->getScenario() == 'budgetContact') {
                    if ($model->sendEmail(Yii::$app->params['adminEmail'], $this->generateBudgetPdf($model))) {
                        Yii::$app->session->setFlash('success', 'Correo con el presupuesto enviado. Le responderemos lo antes posible.');
                        return $this->goHome();
                    } else {
                        Yii::$app->session->setFlash('error', 'Hubo un error al enviar su mensaje.');
                    }

                    return $this->generateBudgetPdf($model);
                }
            }
        }

        return $this->render('budget', [
            'model' => $model,
        ]);
    }

    /**
     * Calculates budget total
     *
     * @param $model
     * @return float|int
     */
    private function calculateBudget($model)
    {
        // 30€ base +
        $budget = 30;
        // 5 € * totalHouseholds +
        $budget += 5 * $model->totalHouseholds;
        /*
            Security (cameras and data protection) 12 €
            Swimming pool (maintenance) 12 €
            Swimming pool (lifeguard) 15 €
            Doorman 20 €
            Janitor 20 €
            Cleaning (company) 12 €
            Cleaning (individual) 15 €
            Gardening (company) 12 €
            Gardening (individual) 15 €
            Other 20 €
         */
        if (!empty($model->contractedServices)) {
            foreach ($model->contractedServices as $service) {
                switch ($service) {
                    case 'swimmingPoolMaintenance':
                    case 'cleaningCompany':
                    case 'gardeningCompany':
                    case 'serviceSecurity':
                        $budget += 12;
                        break;
                    case 'cleaningIndividual':
                    case 'gardeningIndividual':
                    case 'swimmingPoolLifeguard':
                        $budget += 15;
                        break;
                    case 'janitor':
                    case 'otherService':
                    case 'doorman':
                        $budget += 20;
                        break;
                }
            }
        }
        return $budget;
    }

    /**
     * Generates budget pdf
     *
     * @param $model
     * @return mixed
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    private function generateBudgetPdf($model)
    {
        $content = $this->renderPartial('_budgetPdfView', ['model' => $model]);
        $format = new IntlDateFormatter(
            'es',
            IntlDateFormatter::LONG,
            IntlDateFormatter::LONG,
            'Europe/Madrid'
        );
        $date = $format->format(time());
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_STRING,
            'content' => $content,
            'cssInline' => '
            .lines, .header, .notes, .userdata {
                thead-underline: 1px dotted gray;
                width: 75%;
                margin: 2rem auto;
            }
            .total {
                border-top: 1px dotted gray;
            }
            .total-head, .total {
                padding-top: 1rem;
            }
            ',
            'options' => [
                'title' => 'Presupuesto ' . $model->email,
            ],
            'methods' => [
                'SetHeader' => ['Presupuesto ' . $model->email . ' || Generado en: ' . $date],
                'SetFooter' => ['|{PAGENO}|'],
                'SetAuthor' => Yii::$app->name,
                'SetCreator' => Yii::$app->name,
            ]
        ]);

        return $pdf->render();
    }

    /**
     * Displays profile page.
     *
     * @return string
     */
    public function actionProfile(): string
    {
        return $this->render('profile');
    }

    /**
     * Displays notifications page.
     *
     * @return string
     */
    public function actionNotifications(): string
    {
        return $this->render('notifications');
    }

    /**
     * Displays Privacy Policy page.
     *
     * @return string
     */
    public function actionPrivacyPolicy(): string
    {
        return $this->render('privacy-policy');
    }

    /**
     * Requests password reset.
     *
     * @return Response|string
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Consulte su correo electrónico para las siguientes instrucciones.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Lo sentimos, no podemos restablecer la contraseña de la dirección de correo electrónico proporcionada..');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
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
     * Verify email address
     *
     * @param string $token
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail(string $token): Response
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Su correo electrónico ha sido confirmado.');
            return $this->redirect('@siteBack');
        }
        Yii::$app->session->setFlash('error', 'Lo sentimos, no pudimos verificar su cuenta con el token proporcionado.');
        return $this->goHome();
    }
}
