<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
?>

<h1> Benvenuto </h1>

<div class="form-group">
    <?php
        echo "<br>Dati del logopedista loggato: ".Yii::$app->user->getId();
        echo '<br><br>';
        echo Html::a('Esercizio abb', ['/esercizio/creaesercizio?tipologiaEsercizio=abb'], ['class' => 'btn btn-outline-secondary']);
        echo '<br><br>';
        echo Html::a('Esercizio let', ['/esercizio/creaesercizio?tipologiaEsercizio=let'], ['class' => 'btn btn-outline-secondary']);

    ?>

</div>
