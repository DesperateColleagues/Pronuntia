<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\ButtonDropdown;
use yii\bootstrap4\Html;
?>

<h1> Benvenuto </h1>

<div class="form-group">
    <?php
        echo "<br>Benvenuto nella tua dashboard, ".Yii::$app->user->getId().". Scegli una delle seguenti opzioni per gestire i tuoi assistiti.";
        echo '<br><br><h3>Opzioni</h3>';

        echo ButtonDropdown::widget([
            'label' => 'Inserimento nuovo esercizio',
            'dropdown' => [
                'items' => [
                    ['label' => 'Esercizio di abbinamento parole', 'url' => '/esercizio/creaesercizioview?tipologiaEsercizio=abb'],
                    ['label' => 'Esercizio di ripetizione parola', 'url' => '/esercizio/creaesercizioview?tipologiaEsercizio=par'],
                ],
            ],
        ]);

        echo ButtonDropdown::widget([
            'label' => 'Gestione serie esercizi',
            'dropdown' => [
                'items' => [
                    ['label' => 'Creazione nuova serie', 'url' => '/esercizio/creaserieview'],
                    ['label' => 'Assegnazione serie ad utente', 'url' => '/esercizio/assegnaserieview'],
                ],
            ],
        ]);

        //echo Html::a('Esercizio abbinamento', ['/esercizio/creaesercizioview?tipologiaEsercizio=abb'], ['class' => 'btn btn-primary mr-2']);
        //echo Html::a('Esercizio lettura parole', ['/esercizio/creaesercizioview?tipologiaEsercizio=par'], ['class' => 'btn btn-primary mr-2']);
        //echo Html::a('Crea Serie', ['/esercizio/creaserieview'], ['class' => 'btn btn-primary mr-2']);
        //echo Html::a('Assegna Serie', ['/esercizio/assegnaserieview'], ['class' => 'btn btn-primary mr-2']);
    ?>

</div>
