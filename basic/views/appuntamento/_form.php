<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AppuntamentoModel */
/* @var $diaModel app\models\DiagnosiModel */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="appuntamento-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dataAppuntamento')->textInput()?>

    <?= $form->field($model, 'oraAppuntamento')->textInput()?>

    <?php if(isset($diaModel)) {

        echo $form->field($diaModel, 'mediaFile')->fileInput();

        $id = uniqid("",true);

        $hiddenField_idDiagnosi = $form->field($diaModel, 'id')
        ->hiddenInput(['value' => $id])->label(false);

        echo $hiddenField_idDiagnosi;
    }

    ?>

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
