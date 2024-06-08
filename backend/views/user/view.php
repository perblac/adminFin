<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Borrar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Esto borrará la entrada de la base de datos, y no se puede deshacer. Para una eliminación no destructiva puede cambiar el estado de la cuenta a \'Eliminado\'.' .
                    "\n¿Está seguro de que quiere borrar este usuario?",
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'fullname',
            'email:email',
            'address',
            'phone1',
            'phone2',
            'role',
            'status',
            'created_at:datetime',
            'updated_at:datetime',
            'auth_key',
            'password_hash',
            'password_reset_token',
//            'verification_token',
        ],
    ]) ?>

</div>
