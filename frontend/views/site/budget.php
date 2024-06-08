<?php

/** @var yii\web\View $this */
/** @var frontend\models\BudgetForm $model */
/** @var yii\bootstrap5\ActiveForm $form */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = 'Presupuesto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-budget container">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Rellene este formulario para conocer un presupuesto aproximado (sin compromiso)</p>
    <div class="row">
        <div class="col-lg-8 mx-lg-auto">
            <?php $form = ActiveForm::begin(['id' => 'formBudget', 'enableClientScript' => true]); ?>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <?= $form->field($model, 'floors')->textInput(['type' => 'number', 'min' => 1]) ?>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <?= $form->field($model, 'floorHouseholds')->textInput(['type' => 'number', 'min' => 1]) ?>
                </div>
                <div class="col-12 col-md-4">
                    <?= $form->field($model, 'totalHouseholds')->textInput(['type' => 'number', 'min' => 1]) ?>
                </div>
            </div>
            <div class="row d-flex flex-column flex-sm-row align-items-sm-end">
                <div class="col-12 col-sm-8">
                    <?= $form->field($model, 'contractedServices')->checkboxList(
                        $model->getContractedServicesList(),
                    ) ?>
                </div>
                <div class="col-12 col-sm-4 text-center text-sm-end">
                    <?= Html::submitButton('Calcular', ['class' => 'btn btn-primary', 'name' => 'calculate-button', 'id' => 'calculate-button']) ?>
                </div>
            </div>

            <?php if ($model->budgetTotal): ?>
                <p class="border-top">Precio aproximado presupuestado: <?= $model->budgetTotal ?> € / mes</p>
            <?php endif; ?>
        </div>
        <hr class="col-lg-8 mx-lg-auto">
        <div class="container col-lg-8 mx-lg-auto">
            <p class="text-md-center">Si lo desea, nos pondremos en contacto con vd. respecto a este presupuesto</p>
            <div class="col-md-8 mx-md-auto">

                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'annotations')->textarea() ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>
                <?= $form->field($model, 'budgetContact')->hiddenInput(['value' => false])->label(false) ?>

                <div class="text-center text-sm-end">
                    <?= Html::button('Enviar', ['class' => 'btn btn-primary', 'name' => 'budget-contact-button', 'id' => 'budget-contact']) ?>
                </div>
            </div>

            <?php $this->registerJS('
            $("#budget-contact").click(function() {
                $("#budgetform-budgetcontact").val(true);
                $("#formBudget").submit();
            });
            $("#calculate-button").click(function() {
                $("#budgetform-budgetcontact").val(false);
            });
            $("#budgetform-name").on("keypress", function (e) {
                if(e.which == 13) {
                    e.preventDefault();
                    $("#budget-contact").trigger("click");
                }
            });
            $("#budgetform-email").on("keypress", function (e) {
                if(e.which == 13) {
                    e.preventDefault();
                    $("#budget-contact").trigger("click");
                }
            });
            $("#budgetform-verifycode").on("keypress", function (e) {
                if(e.which == 13) {
                    e.preventDefault();
                    $("#budget-contact").trigger("click");
                }
            });
        ') ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>