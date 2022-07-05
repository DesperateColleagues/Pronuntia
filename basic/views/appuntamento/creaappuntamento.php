<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AppuntamentoModel */

$this->title = 'Create Appuntamento Model';
$this->params['breadcrumbs'][] = ['label' => 'Appuntamento Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appuntamento-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('appuntamentoform', [
        'model' => $model,
        'diaModel' => null
    ]) ?>

</div>
