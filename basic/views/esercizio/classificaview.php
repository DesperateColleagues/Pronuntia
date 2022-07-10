<?php

/** @var array $entries */


use app\models\FacadeEsercizio;
use yii\helpers\Html;

$facade = new FacadeEsercizio();
$entries = $facade->generaClassifica();

echo '<h1> Ottimo lavoro!! </h1>'; // messaggio motivazionale

/**
 * @param array $entries
 * @return void
 */
function extracted(array $entries)
{
    echo '<table class="table table-striped">';
    echo '<tr align="center">';
    echo '<td><h5>Username</h5></td>';
    echo '<td><h5>Punteggio</h5></td>';
    echo '</tr>';

    for ($i = 0; $i < sizeof($entries); $i++) {
        if (array_keys($entries)[$i] == $_COOKIE['utente']) {
            echo '<tr align="center" class="table-info">';
        } else {
            echo '<tr align="center">';
        }
        echo '<td> ' . array_keys($entries)[$i] . '</td>'; // nome utente
        echo '<td>' . $entries[array_keys($entries)[$i]] . '</td>'; // punteggio
        echo '</tr>';
    }
    echo '</table>';
}

extracted($entries);

echo '<br>';
echo Html::a('Torna alla dashboard', ['/utente/dashboardutente'], ['class' => 'btn btn-outline-secondary']);
