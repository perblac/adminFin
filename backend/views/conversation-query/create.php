<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Conversation $model */

$this->title = 'Create Conversation';
$this->params['breadcrumbs'][] = ['label' => 'Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
