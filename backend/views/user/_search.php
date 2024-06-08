<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\UserQuery $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

<!--    --><?php //= $form->field($model, 'auth_key') ?>

<!--    --><?php //= $form->field($model, 'password_hash') ?>

<!--    --><?php //= $form->field($model, 'password_reset_token') ?>

    <?php  echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'verification_token') ?>

    <?php  echo $form->field($model, 'address') ?>

    <?php  echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'fullname') ?>


    <?php // echo $form->field($model, 'phone1') ?>

    <?php // echo $form->field($model, 'phone2') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
