<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

use yii\bootstrap4\ButtonDropdown;
use yii\helpers\Html;

?>

<h1> Benvenuto </h1>

<div class="form-group">
    <?php
    echo "Benvenuto nella tua dashboard, " . Yii::$app->user->getId() . ".<br>Scegli una delle seguenti opzioni per gestire i tuoi assistiti.";
    echo '<br><br>';
    ?>

    <div class="row">
        <div class="col text-center">
            <h4>Menù Esercizi</h4>

            <?php
            echo ButtonDropdown::widget([
                'label' => 'Inserimento nuovo esercizio',
                'dropdown' => [
                    'items' => [
                        ['label' => 'Esercizio di abbinamento parole', 'url' => '/esercizio/creaesercizioview?tipologiaEsercizio=abb'],
                        ['label' => 'Esercizio di ripetizione parola', 'url' => '/esercizio/creaesercizioview?tipologiaEsercizio=par'],
                    ],
                ],
                'buttonOptions' => ['class' => 'btn-outline-primary mr-2']
            ]);
            ?>
        </div>

        <div class="col text-center">
            <h4>Menù Serie</h4>

            <?php

            echo ButtonDropdown::widget([
                'label' => 'Gestione serie esercizi',
                'dropdown' => [
                    'items' => [
                        ['label' => 'Creazione nuova serie', 'url' => '/esercizio/creaserieview'],
                        ['label' => 'Assegnazione serie ad utente', 'url' => '/esercizio/assegnaserieview'],
                    ],
                ],
                'buttonOptions' => ['class' => 'btn-outline-primary']
            ]);
            ?>
        </div>
    </div>
    <br><br>
    <div class="text-center">
        <h4>Monitoraggio svolgimento esercizi</h4>
        <?php echo Html::a('Monitora gli utenti', ['monitoraggioterapialogopedistaview'], ['class' => 'btn btn-outline-primary']); ?>
    </div>
</div>