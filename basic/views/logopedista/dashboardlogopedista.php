<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
?>

<h1> Benvenuto </h1>

<div class="form-group">
    <?= Html::submitButton('Registra assistito', ['class' => 'btn btn-primary', 'name' => 'reg-button']) ?>
    <?php
        echo "<br>Dati del logopedista loggato: ".Yii::$app->user->getId();
    ?>

</div>
