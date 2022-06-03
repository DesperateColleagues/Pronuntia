<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AppuntamentoModelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appuntamento-model-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dataAppuntamento') ?>

    <?= $form->field($model, 'oraAppuntamento') ?>

    <?= $form->field($model, 'logopedista') ?>

    <?= $form->field($model, 'utente') ?>

    <?= $form->field($model, 'caregiver') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
