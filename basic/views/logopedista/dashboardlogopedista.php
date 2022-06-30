<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
?>

<h1> Benvenuto </h1>

<div class="form-group">
    <?php
        echo "<br>Benvenuto: ".Yii::$app->user->getId();
        echo '<br><br><h3>Opzioni</h3>';
        echo Html::a('Esercizio abbinamento', ['/esercizio/creaesercizioview?tipologiaEsercizio=abb'], ['class' => 'btn btn-primary mr-2']);
        echo Html::a('Esercizio lettura', ['/esercizio/creaesercizioview?tipologiaEsercizio=let'], ['class' => 'btn btn-primary mr-2']);
        echo Html::a('Visualizza esercizi', ['/esercizio/visualizzaeserciziview'], ['class' => 'btn btn-primary mr-2']);
        echo Html::a('Crea SerieModel', ['/esercizio/creaserieview'], ['class' => 'btn btn-primary mr-2']);
    ?>

</div>
