<?php

use yii\helpers\Html;
use yii\helpers\VarDumper;
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

    <?php if(isset($diaModel) && $model->utente != null) {  // Permette il caricamento della diagnosi solo ed esclusivamente
                                                            // se alla view viene passato un model di tipo diagnosi. Questo
                                                            // accadrà solamente quando a richiamare questo form è la view
                                                            // d'update. Inoltre, il valore dell'utente nel model
                                                            // dell'appuntamento non deve essere nullo, ovvero l'appuntamento
                                                            // deve essere stato prenotato da un assistito.

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
        <?= Html::submitButton('Salva', ['class' => 'btn btn-success']) ?>
        <?php 
        if(isset($_COOKIE['CurrentActor'])) {
            $tipoAttore = $_COOKIE['CurrentActor'];
        }
        echo Html::a('Torna agli appuntamenti',['index','tipoAttore' => $tipoAttore],['class' => 'btn btn-outline-secondary']);
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
