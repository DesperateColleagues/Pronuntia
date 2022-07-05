<?php

use app\models\ComposizioneserieModel;
use kartik\sortinput\SortableInput;
use yii\helpers\Html;

/* @var $tipologiaEsercizio string */
/* @var $soluzioni array */
/* @var $pathnames array */

Yii::error($tipologiaEsercizio);

if ($tipologiaEsercizio == 'abb') {

    echo "<table style = 'margin-left:auto;margin-right:auto'>";
    echo '<tr>';

    foreach ($pathnames as $path) {
        echo '<td>' . Html::img($path, ['style' => 'width:300px']) . '</td>';
    }

    echo '</tr>';
    echo '<tr align="center">';
    for ($i = 0; $i < sizeof($soluzioni); $i++) {
        $index = $i + 1;
        echo '<td>' . "$index" . ' </td>';
    }
    echo '</tr>';

    echo '</table>';
    echo Html::beginForm();
    $items = [];
    $values = [];

    for ($i = 0; $i < sizeof($soluzioni); $i++) {
        $values[$i] = ['content' => '<i class="fas fa-cog"></i>' . $soluzioni[$i]];
    }

    $items = array_fill_keys($soluzioni, 0);

    for ($i = 0; $i < sizeof($soluzioni); $i++) {
        $items[$soluzioni[$i]] = $values[$i];
    }

    echo SortableInput::widget([
        'name' => 'sort_list_1',
        'items' => $items,
        'hideInput' => false,
        'options' => [
            'class' => 'form-control', 'readonly' => true
        ]
    ]);

    echo '<br>';
    echo Html::submitButton('Fine esercizio',
        ['class' => 'btn btn-primary mr-2', 'name' => 'confermaRispostaAbbinamento']);
    echo Html::endForm();

} else if ($tipologiaEsercizio == 'par'){

    echo "<table style = 'margin-left:auto;margin-right:auto'>";

    echo '<tr align="center">';
    echo '<td>' . Html::img($pathnames[0], ['style' => 'width:300px']) . '</td>';
    echo '</tr>';

    echo '<tr align="center">';
    echo '<td>' . "$soluzioni[0]" . ' </td>';
    echo '</tr>';

    echo '</table>';

    echo Html::beginForm();
    echo Html::submitButton('Fine esercizio',
        ['class' => 'btn btn-primary mr-2', 'name' => 'confermaRispostaLettura']);
    echo Html::endForm();
}
