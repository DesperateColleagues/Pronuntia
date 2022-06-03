<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AppuntamentoModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appuntamento-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dataAppuntamento')->textInput() ?>

    <?= $form->field($model, 'oraAppuntamento')->textInput() ?>

    <?php
        $logopedistaEmail = Yii::$app->user->getId();

        $hiddenFieldLogopedista = $form->field($model, 'logopedista')
        ->hiddenInput(['value' => $logopedistaEmail])->label(false);

        echo $hiddenFieldLogopedista;

        Yii::error($logopedistaEmail);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
