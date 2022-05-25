<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

// parametri della VIEW in cui inserire anche il model
/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $attore*/ // tipo di attore di cui si sta effettuando la registrazione
/* @var $caregiverEmail */ // valorizzata SOLO quando si sta registrando l'utente non autonomo

// costanti da confrontare con il valore di $attore
$UTENTE_NON_AUTONOMO = 'utn';
$UTENTE_AUTONOMO = 'uta';
$CAREGIVER = 'car';

$titolo = 'Logopedista';

// imposta un titolo alla schermata di registrazione
if ($attore == $UTENTE_AUTONOMO)
    $titolo = 'Utente autonomo';
else if ($attore == $UTENTE_NON_AUTONOMO)
    $titolo = 'Utente non autonomo'.'<br>'.'caregiver: '.$caregiverEmail;
else if ($attore == $CAREGIVER)
    $titolo = 'Caregiver';
?>

<div class="Registrazione">

    <div class="row">
        <div class="col-lg-5">

            <h2> Registrazione </h2>
            <h5> <?php echo $titolo?> </h5>

            <p>
                <!-- Costruzione del form !-->

                <?php
                $form = ActiveForm::begin(['id' => 'contact-form']); // inizia la costruzione dell'active form

                /**
                 * Campi variabili: i seguenti campi sono presenti nel form a seconda dell'attore che si sta regitrando
                 * 1) $fieldUsername:   username dell'utente. Questo campo è mostrato solo se $attore = $UTENTE_AUTONOMO
                 *                      o $attore = $UTENTE_NON_AUTONOMO
                 *
                 * 2) $$hiddenFieldLogopedista: campo nascosto. Conterrà la mail del logopedista.
                 *                              Questo campo è valorizzato solo se si registra un utente
                 *
                 * 3) $hiddenFieldCaregiver:    campo nascosto. Conterrà la mail del caregiver.
                 *                              Questo campo è valorizzato solo se si registra un utente non autonomo
                */
                $fieldUsername = null;
                $hiddenFieldLogopedista = null;
                $hiddenFieldCaregiver = null;

                /**
                 * In questi rami if, in base all'attore che si sta registrando:
                 *  - Si istanzia lo specifico model da usare nel form
                 *  - Per gli utenti, a seconda del tipo, si valorizzano i campi variabili e si istanzia il
                 *    relativo model
                */
                if ($attore == $UTENTE_AUTONOMO) {
                    $model = new \app\models\UtenteModel();

                    // l'email del logopedista è recuperata della proprietà user di $app
                    $logopedistaEmail = Yii::$app->user->getId();

                    $fieldUsername = $form->field($model, 'username');

                    $hiddenFieldLogopedista = $form->field($model, 'logopedista')
                        ->hiddenInput(['value' => $logopedistaEmail])->label(false);
                } else if ($attore == $UTENTE_NON_AUTONOMO) {
                    $model = new \app\models\UtenteModel();

                    // l'email del logopedista è recuperata della proprietà user di $app
                    $logopedistaEmail = Yii::$app->user->getId();

                    $fieldUsername = $form->field($model, 'username');

                    $hiddenFieldLogopedista = $form->field($model, 'logopedista')
                        ->hiddenInput(['value' => $logopedistaEmail])->label(false);

                    $hiddenFieldCaregiver = $form->field($model, 'caregiver')
                        ->hiddenInput(['value' => $caregiverEmail])->label(false);
                } else if ($attore == $CAREGIVER) {
                    $model = new \app\models\CaregiverModel();
                } else {
                    $model = new \app\models\LogopedistaModel();
                }

                // echo dei campi del form
                echo $form->field($model, 'nome');
                echo $form->field($model, 'cognome');
                echo $form->field($model, 'dataNascita');
                echo $form->field($model, 'email');
                // i campi variabili non compaiono nel form se NON sono valorizzati
                echo $fieldUsername; // campo variabile
                echo $hiddenFieldLogopedista; // campo variabile
                echo $hiddenFieldCaregiver; // campo variabile
                echo $form->field($model, 'passwordD')->passwordInput();

                echo '<div class="form-group">';
                    //echo Html::submitButton('Indietro', ['class' => 'btn', 'url' => ['/logopedista/dashboardlogopedista']]);
                    echo Html::submitButton('Registra', ['class' => 'btn btn-primary', 'name' => 'contact-button']);

                echo '</div>';

                ActiveForm::end(); ?>
            </p>
</div>