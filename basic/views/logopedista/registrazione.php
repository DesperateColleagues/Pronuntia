<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

// parametri della VIEW in cui inserire anche il model
/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model app\models\FormRegistrazione*/
?>

<div class="Registrazione">

    <div class="row">
        <div class="col-lg-5">

            <h2>Registrazione logopedista</h2>

            <p>
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'nome')?>

                <?= $form->field($model, 'cognome') ?>

                <?= $form->field($model, 'dataNascita') ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Registrati', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </p>

        </div>

</div><!-- Registrazione -->