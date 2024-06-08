<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;
use yii\db\Exception;

class AdminController extends Controller
{
    public $username;
    public $password;

    public function options($actionID): array
    {
        return ['username', 'password'];
    }

    public function optionAliases(): array
    {
        return ['u' => 'username', 'p' => 'password'];
    }

    public function actionIndex()
    {
        echo $this->username . "\n" . $this->password . "\n";
    }

    /**
     * @throws Exception
     */
    public function actionCreate(): bool
    {
        echo $this->username . "\n" . $this->password . "\n";

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->username . '@[change.me]';
        $user->setPassword($this->password);
        $user->role = 'admin';
        $user->status = 10;
        $user->generateAuthKey();

        return $user->save();
    }
}
