<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

// parametri della VIEW in cui inserire anche il model
/* @var $this yii\web\View */
/* @var $caregiverEmail */
/* @var $form ActiveForm */
/* @var $attore*/

$UTENTE_NON_AUTONOMO = 'utn';
$UTENTE_AUTONOMO = 'uta';
$CAREGIVER = 'car';
$echoPar = 'Logopedista';

// todo: implementare registrazione autente autonomo e caregiver
if ($attore == $UTENTE_AUTONOMO)
    $echoPar = 'Utente autonomo';
else if ($attore == $UTENTE_NON_AUTONOMO)
    $echoPar = 'Utente non autonomo'.'<br>'.'caregiver: '.$caregiverEmail;
else if ($attore == $CAREGIVER)
    $echoPar = 'Caregiver';
?>

<div class="Registrazione">

    <div class="row">
        <div class="col-lg-5">

            <h2> Registrazione </h2>
            <h3> <?php echo $echoPar?> </h3>

            <p>
                <?php
                $model = new \app\models\LogopedistaModel();
                $form = ActiveForm::begin(['id' => 'contact-form']);
                $fieldUsername = null;
                $hiddenFieldLogopedista = null;
                $hiddenFieldCaregiver = null;

                if ($attore == $UTENTE_AUTONOMO) {
                    $logopedistaEmail = Yii::$app->user->getId();
                    Yii::error(Yii::$app->user->getId());
                    $model = new \app\models\UtenteModel();
                    $fieldUsername = $form->field($model, 'username');
                    $hiddenFieldLogopedista = $form->field($model, 'logopedista')
                        ->hiddenInput(['value' => $logopedistaEmail])->label(false);
                }
                else if ($attore == $CAREGIVER) {
                    $model = new \app\models\CaregiverModel();
                }
                else if ($attore == $UTENTE_NON_AUTONOMO) {
                    $logopedistaEmail = Yii::$app->user->getId();
                    $model = new \app\models\UtenteModel();
                    $fieldUsername = $form->field($model, 'username');

                    $hiddenFieldLogopedista = $form->field($model, 'logopedista')
                        ->hiddenInput(['value' => $logopedistaEmail])->label(false);

                    Yii::error($caregiverEmail);
                    $hiddenFieldCaregiver = $form->field($model, 'caregiver')
                        ->hiddenInput(['value' => $caregiverEmail])->label(false);
                }

                echo $form->field($model, 'nome');
                echo $form->field($model, 'cognome');
                echo $form->field($model, 'dataNascita');
                echo $form->field($model, 'email');
                echo $fieldUsername;
                echo $hiddenFieldLogopedista;
                echo $hiddenFieldCaregiver;
                echo $form->field($model, 'passwordD')->passwordInput();
            echo '<div class="form-group">';
                 echo Html::submitButton('Registrati', ['class' => 'btn btn-primary', 'name' => 'contact-button']);
            echo '</div>';

            ActiveForm::end(); ?>
            </p>

</div><!-- Registrazione -->