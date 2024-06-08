<?php

use common\models\User;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use powerkernel\fontawesome\Icon;

/** @var yii\web\View $this */
/** @var backend\models\UserQuery $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Administrar usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo cliente', Url::to(['site/create-account']), ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            'username',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
            'email:email',
            [
                'attribute' => 'status',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'prompt' => '(todos)      ',
                ],
                'filter' => $searchModel->getStatusTypes(),
                'value' => function ($model) {
                    return $model->getStatusValue($model->status);
                }
            ],
            //'created_at',
            //'updated_at',
            //'verification_token',
            'address',
            [
                'attribute' => 'role',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'prompt' => '(todos)      ',
                ],
                'filter' => $searchModel->getRoleTypes(),
                'value' => function ($model) {
                    return $model->getRoleValue($model->role);
                }
            ],
            //'fullname',
            //'phone1',
            //'phone2',
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete} {resendVerificationMail}',
                'urlCreator' => function ($action, User $model) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a(Icon::widget(['name' => 'trash']), ['delete', 'id' => $model->id], [
                            'class' => '',
                            'data' => [
                                'confirm' => 'Esto borrará la entrada de la base de datos, y no se puede deshacer. Para una eliminación no destructiva puede cambiar el estado de la cuenta a \'Eliminado\'.' .
                                    "\n¿Está seguro de que quiere borrar este usuario?",
                                'method' => 'post',
                            ],
                        ]);
                    },
                    'resendVerificationMail' => function ($url, $model) {
                        if ($model->role === 'client' && $model->status === User::STATUS_INACTIVE) {
                            return Html::a(
                                Icon::widget(['name' => 'envelope']),
                                ['resend-verification-mail', 'id' => $model->id],
                                [
                                    'title' => 'Reenviar correo de verificación',
                                    'data-toggle' => 'tooltip',
                                ]
                            );
                        }
                    }
                ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
