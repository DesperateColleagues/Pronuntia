<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

// parametri della VIEW in cui inserire anche il model
/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $attore*/

const UTENTE_NON_AUTONOMO = 'utn';
const UTENTE_AUTONOMO = 'uta';
const CAREGIVER = 'car';
// todo: implementare registrazione autente autonomo e caregiver

?>

<div class="Registrazione">

    <div class="row">
        <div class="col-lg-5">

            <h2>Registrazione</h2>

            <p>
                <?php
                $model = new \app\models\LogopedistaModel();
                $form = ActiveForm::begin(['id' => 'contact-form']);
                $fieldUsername = null;
                $hiddenFieldEmail = null;

                if ($attore == UTENTE_AUTONOMO) {
                    $logopedistaEmail = Yii::$app->user->getId();
                    Yii::info(Yii::$app->user->getId());
                    $model = new \app\models\UtenteModel();
                    $fieldUsername = $form->field($model, 'username');
                    $hiddenFieldEmail = $form->field($model, 'logopedista')
                        ->hiddenInput(['value' => $logopedistaEmail])->label(false);
                }
                else if ($attore == CAREGIVER)
                    $model = new \app\models\CaregiverModel();

                echo $form->field($model, 'nome');
                echo $form->field($model, 'cognome');
                echo $form->field($model, 'dataNascita');
                echo $form->field($model, 'email');
                echo $fieldUsername;
                echo $hiddenFieldEmail;
                echo $form->field($model, 'passwordD')->passwordInput();
            echo '<div class="form-group">';
                 echo Html::submitButton('Registrati', ['class' => 'btn btn-primary', 'name' => 'contact-button']);
            echo '</div>';

            ActiveForm::end(); ?>
            </p>

</div><!-- Registrazione -->