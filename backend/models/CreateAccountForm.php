<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\db\Exception;

/**
 * Signup form
 */
class CreateAccountForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $fullname;
    public $address;
    public $phone1;
    public $phone2;

    public function attributeLabels()
    {
        return [
            'username' => 'Nombre de usuario',
            'password' => 'Contraseña',
            'fullname' => 'Nombre completo',
            'address' => 'Dirección',
            'phone1' => 'Teléfono 1',
            'phone2' => 'Teléfono 2',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            ['fullname', 'trim'],
            ['fullname', 'required'],

            ['address', 'trim'],
            ['address', 'required'],

            ['phone1', 'trim'],
            ['phone2', 'trim'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     * @throws Exception
     */
    public function signup(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->fullname = $this->fullname;
        $user->address = $this->address;
        $user->phone1 = $this->phone1;
        $user->phone2 = $this->phone2;
        $user->role = 'client';
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save() && $this->sendEmail($user, $this->password);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail(User $user, $pass): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                [
                    'user' => $user,
                    'pass' => $pass,
                ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Registro de cuenta en ' . Yii::$app->name)
            ->send();
    }
}
