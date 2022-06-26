<?php

use app\models\DiagnosiModel;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AppuntamentoModel */
/* @var $diaModel app\models\DiagnosiModel */

$this->title = 'Modifica Appuntamento: ' . $model->dataAppuntamento . " - " . $model->oraAppuntamento;
$this->params['breadcrumbs'][] = ['label' => 'Appuntamento Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dataAppuntamento, 'url' => ['view', 'dataAppuntamento' => $model->dataAppuntamento, 'oraAppuntamento' => $model->oraAppuntamento, 'logopedista' => $model->logopedista]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="appuntamento-model-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Yii::error($model->attributes);?>

    <?= $this->render('appuntamentoform', [
        'model' => $model,
        'diaModel' => $diaModel
    ]) ?>

</div>
