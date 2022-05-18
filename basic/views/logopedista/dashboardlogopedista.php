<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var $message */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;?>


<h1> Benvenuto <?php echo $message?> </h1>

<div class="form-group">
    <?= Html::submitButton('Registra assistito', ['class' => 'btn btn-primary', 'name' => 'reg-button']) ?>
</div>
