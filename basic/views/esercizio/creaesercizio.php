<?php

use app\models\EsercizioModel;
use yii\helpers\Html;
use app\models\ImmagineEsercizioModel;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ImmagineEsercizioModel */
/* @var $tipologiaEsercizio */
/* @var $nomeEsercizio */

$this->title = 'Creazione nuovo esercizio';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="esercizio-model-create-new">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php
    $form = ActiveForm::begin(['id' => 'inizia-form-nuovo-esercizio']);

    if ($tipologiaEsercizio == 'abb') {

        if ($nomeEsercizio == null) {

            echo $form->field($model, 'nomeEsercizio')->textInput()->label('Nome esercizio');
            echo $form->field($model, 'nomeImmagine')->textInput()->label('Nome Immagine');
            echo $form->field($model, 'file')->fileInput();

            echo Html::submitButton('Conferma', ['class' => 'btn btn-primary mr-1',  'name' => 'continue-button']);
        } else if($nomeEsercizio != null) {

            echo $form->field($model, 'nomeEsercizio')->textInput()->label($nomeEsercizio);
            echo $form->field($model, 'nomeImmagine')->textInput()->label('Nome Immagine');
            echo $form->field($model, 'file')->fileInput();

            echo Html::submitButton('Conferma', ['class' => 'btn btn-primary mr-1',  'name' => 'continue-button']);
            echo Html::submitButton('Termina inserimento', ['class' => 'btn btn-primary',  'name' => 'end-button']);
        }
    } else if ($tipologiaEsercizio == 'let') {
    }
    ?>

    <?php ActiveForm::end(); ?>


    <?php echo Html::a('Torna alla dashboard', ['/logopedista/dashboardlogopedista?tipoAttore=log'], ['class' => 'btn btn-outline-secondary']); ?>

</div>