<?php

namespace common\models;

use backend\models\Notification;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $fullname
 * @property string $email
 * @property string $address
 * @property string $phone1
 * @property string $phone2
 * @property integer $status
 * @property string $role
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_ADMIN = 'admin';
    const ROLE_CLIENT = 'client';

    const SCENARIO_UPDATE_PROFILE = 'updateProfile';
    const SCENARIO_UPDATE_ADMIN_PROFILE = 'updateAdminProfile';
    const SCENARIO_UPDATE_USER = 'updateUser';

    public function attributeLabels()
    {
        return [
            'username' => 'Nombre de usuario',
            'password' => 'Contraseña',
            'fullname' => 'Nombre completo',
            'address' => 'Dirección',
            'phone1' => 'Teléfono 1',
            'phone2' => 'Teléfono 2',
            'auth_key' => 'Clave de autorización',
            'password_hash' => 'Hash de contraseña',
            'password_reset_token' => 'Token de restablecimiento de contraseña',
            'role' => 'Rol',
            'status' => 'Estado de la cuenta',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de actualización',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED], 'on' => ['default', self::SCENARIO_UPDATE_USER]],
            ['role', 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_CLIENT], 'on' => ['default', self::SCENARIO_UPDATE_USER]],
            [['fullname', 'email', 'address'], 'required', 'on' => self::SCENARIO_UPDATE_PROFILE],
            ['email', 'email', 'on' => self::SCENARIO_UPDATE_PROFILE],
            ['username', 'required', 'on' => self::SCENARIO_UPDATE_USER],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE_PROFILE] = ['fullname', 'email', 'address', 'phone1', 'phone2'];
        $scenarios[self::SCENARIO_UPDATE_ADMIN_PROFILE] = ['fullname', 'email', 'address', 'phone1', 'phone2'];
        $scenarios[self::SCENARIO_UPDATE_USER] = ['username', 'fullname', 'email', 'address', 'phone1', 'phone2', 'role', 'status',];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     */
    public function setFullname(string $fullname): void
    {
        $this->fullname = $fullname;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone1(): string
    {
        return $this->phone1;
    }

    /**
     * @param string $phone1
     */
    public function setPhone1(string $phone1): void
    {
        $this->phone1 = $phone1;
    }

    /**
     * @return string
     */
    public function getPhone2(): string
    {
        return $this->phone2;
    }

    /**
     * @param string $phone2
     */
    public function setPhone2(string $phone2): void
    {
        $this->phone2 = $phone2;
    }

    public function getStatusTypes(): array
    {
        return [
            self::STATUS_DELETED => 'Eliminada',
            self::STATUS_INACTIVE => 'Inactiva',
            self::STATUS_ACTIVE => 'Activa',
        ];
    }

    public function getStatusValue($status): string
    {
        return $this->getStatusTypes()[$status];
    }

    public function getRoleTypes(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_CLIENT => 'Cliente',
        ];
    }

    public function getRoleValue($role): string
    {
        return $this->getRoleTypes()[$role];
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getUnreadNotifications(): array
    {
        return Notification::find()
            ->where(['status' => Notification::MESSAGE_STATUS_UNREAD])
            ->andWhere(['receiver_id' => Yii::$app->user->getId()])
            ->all();
    }
}
