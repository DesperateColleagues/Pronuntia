<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

use yii\helpers\Html;
?>

<h1> Benvenuto </h1>

<div class="form-group">
    <?php
    echo "<br>Monitoraggio pazienti di " . Yii::$app->user->getId();

    echo "<br>";

    echo Html::a('Torna alla dashboard', ['/logopedista/dashboardlogopedista?tipoAttore=log'], ['class' => 'btn btn-outline-secondary']);

    ?>

</div>