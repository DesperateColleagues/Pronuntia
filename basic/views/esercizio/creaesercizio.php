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
            echo Html::submitButton('Conferma', ['class' => 'btn btn-primary mr-1',  'name' => 'confirm-button']);
        } else {
            echo '<p>';
            echo '<li>Premere il bottone <b>Continua inserimento</b> per inserire le immagini</li>';
            echo '<li>Premere il bottone <b>Torna alla dashboard</b> per terminare</li>';
            echo '</p>';
            echo $form->field($model, 'nomeEsercizio')->textInput(['readonly' => true, 'value' => $nomeEsercizio]);
            echo $form->field($model, 'nomeImmagine')->textInput()->label('Nome Immagine - Soluzione esercizio');
            echo $form->field($model, 'file')->fileInput();

            echo Html::submitButton('Continua inserimento', ['class' => 'btn btn-primary mr-1',  'name' => 'continue-button']);
            //echo Html::submitButton('Termina inserimento', ['class' => 'btn btn-primary',  'name' => 'end-button']);
        }
    } else if ($tipologiaEsercizio == 'let') {

        $esModel = new EsercizioModel();
        echo $form->field($esModel, 'nome')->textInput()->label('Nome esercizio');
        echo $form->field($esModel, 'testo')->textInput()->label('Testo');

        $hiddenFieldLogopedista = $form->field($esModel, 'logopedista')
            ->hiddenInput(['value' => Yii::$app->user->getId()])->label(false);

        echo $hiddenFieldLogopedista;

        $hiddenFieldTipologia = $form->field($esModel, 'tipologia')
            ->hiddenInput(['value' => 'let'])->label(false);

        echo $hiddenFieldTipologia;

        echo Html::submitButton('Conferma', ['class' => 'btn btn-primary mr-1',  'name' => 'continue-button']);
    }
    ?>

    <?php ActiveForm::end(); ?>


<?php
    echo '<br>';
    echo Html::a('Torna alla dashboard', ['/logopedista/dashboardlogopedista?tipoAttore=log'], ['class' => 'btn btn-outline-secondary']);
?>

</div>