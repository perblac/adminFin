<?php

/** @var yii\web\View $this */

/** @var BudgetForm $model */

use frontend\models\BudgetForm;

?>
<body>
<div class="container">
    <div class="row">
        <div class="col-8">
            <div class="header">
                <h2>Presupuesto para<br><?php echo !empty($model->name) ? $model->name : $model->email ?></h2>
                <?= !empty($model->name) ? $model->email : '' ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <table class="lines">
                <thead>
                <tr>
                    <th>Concepto</th>
                    <th class="text-right">Precio</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Precio base</td>
                    <td class="text-right">30 €</td>
                </tr>
                <tr>
                    <td><?= $model->totalHouseholds ?> vivienda<?= $model->totalHouseholds > 1 ? 's' : '' ?></td>
                    <td class="text-right"><?= 5 * $model->totalHouseholds ?> €</td>
                </tr>
                <?php foreach ($model->contractedServices as $service): ?>
                    <tr>
                        <td><?php
                            switch ($service) {
                                case 'serviceSecurity':
                                    echo 'Seguridad (cámaras y protección de datos)';
                                    break;
                                case 'swimmingPoolMaintenance':
                                    echo 'Piscina (mantenimiento)';
                                    break;
                                case 'swimmingPoolLifeguard':
                                    echo 'Piscina (socorrista)';
                                    break;
                                case 'doorman':
                                    echo 'Portero';
                                    break;
                                case 'janitor':
                                    echo 'Bedel';
                                    break;
                                case 'cleaningCompany':
                                    echo 'Limpieza (empresa)';
                                    break;
                                case 'cleaningIndividual':
                                    echo 'Limpieza (individuo)';
                                    break;
                                case 'gardeningCompany':
                                    echo 'Jardineria (empresa)';
                                    break;
                                case 'gardeningIndividual':
                                    echo 'Jardineria (individuo)';
                                    break;
                                case 'otherService':
                                    echo 'Otro servicio';
                                    break;
                            }
                            ?>
                        </td>
                        <td class="text-right">
                            <?php
                            switch ($service) {
                                case 'swimmingPoolMaintenance':
                                case 'cleaningCompany':
                                case 'gardeningCompany':
                                case 'serviceSecurity':
                                    echo '12 €';
                                    break;
                                case 'cleaningIndividual':
                                case 'gardeningIndividual':
                                case 'swimmingPoolLifeguard':
                                    echo '15 €';
                                    break;
                                case 'janitor':
                                case 'otherService':
                                case 'doorman':
                                    echo '20 €';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot class="border-top border-dark-subtle">
                <tr>
                    <th class="total-head">Total</th>
                    <td class="total text-right pe-2"><?= $model->budgetTotal ?> €</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <?php if (!empty($model->annotations)): ?>
                <div class="notes">
                    <p>Anotaciones:</p>
                    <p>
                        <?= $model->annotations ?>
                    </p>
                </div>
            <?php endif; ?>
            <div class="userdata">
                <p>
                    email: <?= $model->email ?>
                </p>
                <p>
                    ip: <?= Yii::$app->request->userIP ?>
                </p>
                <p>
                    agent: <?= Yii::$app->request->userAgent ?>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
