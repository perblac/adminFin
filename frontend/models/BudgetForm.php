<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class BudgetForm extends Model
{
    // number of floors, households per floor, total number of households
    public $floors;
    public $floorHouseholds;
    public $totalHouseholds;

    /*
        Contracted services:
        Security (cameras and data protection)
        Swimming pool (maintenance)
        Swimming pool (lifeguard)
        Doorman
        Janitor
        Cleaning (company)
        Cleaning (individual)
        Gardening (company)
        Gardening (individual)
        Other
     */
    public $contractedServices = [];

    // Contact
    public $name;
    public $email;
    public $annotations;
    public $verifyCode;
    public $budgetContact;

    // Budget estimated total
    public int $budgetTotal = 0;

    public function scenarios()
    {
        return [
            'default' => [
                'floors',
                'floorHouseholds',
                'totalHouseholds',
                'contractedServices',
                'budgetTotal',
                'budgetContact',
            ],
            'budgetContact' => [
                'floors',
                'floorHouseholds',
                'totalHouseholds',
                'contractedServices',
                'budgetTotal',
                'name',
                'email',
                'annotations',
                'verifyCode',
                'budgetContact',
            ],
        ];
    }

    public function rules()
    {
        return [
            [['floors', 'floorHouseholds'], 'required', 'when' => function ($model) {
                return empty($model->totalHouseholds);
            }, 'whenClient' => "function(attribute, value) {
                return $('#budgetform-totalhouseholds').val() === '';
            }"],
            ['totalHouseholds', 'required', 'when' => function ($model) {
                return empty($model->floors) && empty($model->floorHouseholds);
            }, 'whenClient' => "function(attribute, value) {
                return ($('#budgetform-floors').val() === '' && $('#budgetform-floorhouseholds').val() === '');
            }"],
            [['floors', 'floorHouseholds', 'totalHouseholds'], 'number', 'min' => 1],
            ['email', 'email'],
            [['email', 'verifyCode'], 'required', 'on' => ['budgetContact']],
            ['verifyCode', 'captcha', 'on' => ['budgetContact']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'floors' => 'Número de plantas',
            'floorHouseholds' => 'Viviendas por planta',
            'totalHouseholds' => 'Número total de viviendas',
            'contractedServices' => 'Servicios contratados',
            'name' => 'Nombre',
            'email' => 'Email de contacto',
            'annotations' => 'Anotaciones',
            'verifyCode' => 'Código de verificación',
            'budgetTotal' => 'Precio aproximado:',
        ];
    }

    /**
     * @return array
     */
    public function getContractedServicesList(): array
    {
        return [
            'serviceSecurity' => 'Seguridad (cámaras y protección de datos)',
            'swimmingPoolMaintenance' => 'Piscina (mantenimiento)',
            'swimmingPoolLifeguard' => 'Piscina (socorrista)',
            'doorman' => 'Portero',
            'janitor' => 'Bedel',
            'cleaningCompany' => 'Limpieza (empresa)',
            'cleaningIndividual' => 'Limpieza (individuo)',
            'gardeningCompany' => 'Jardineria (empresa)',
            'gardeningIndividual' => 'Jardineria (individuo)',
            'otherService' => 'Otro',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail(string $email, $file): bool
    {
        $subject = 'Contacto mediante formulario de presupuesto';
        $body = 'Presupuesto generado en el formulario de contacto';
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$this->email => 'formulario-presupuesto(' . Yii::$app->request->getUserIP() . ')'])
            ->setSubject($subject . ' (' . Yii::$app->name . ' budget form)')
            ->setTextBody($body)
            ->attachContent($file, ['fileName' => 'presupuesto.pdf', 'contentType' => 'application/pdf'])
            ->send();
    }
}