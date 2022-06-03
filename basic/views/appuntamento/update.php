<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AppuntamentoModel */

$this->title = 'Update Appuntamento Model: ' . $model->dataAppuntamento;
$this->params['breadcrumbs'][] = ['label' => 'Appuntamento Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dataAppuntamento, 'url' => ['view', 'dataAppuntamento' => $model->dataAppuntamento, 'oraAppuntamento' => $model->oraAppuntamento, 'logopedista' => $model->logopedista]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="appuntamento-model-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
