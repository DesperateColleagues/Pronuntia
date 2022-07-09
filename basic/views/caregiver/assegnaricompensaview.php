<?php

use yii\helpers\Html;

/* @var $tipologiaEsercizio string */
/* @var $pathnames array */

echo "<table style = 'margin-left:auto;margin-right:auto'>";
echo '<tr>';

$columnCount = 0;

echo Html::beginForm();

foreach ($pathnames as $path) {

    /*$image = Html::img($path, ['style' => 'width:70px']);

    Yii::error($path);

    echo Html::button();*/

    echo '<td>' . Html::img($path, ['style' => 'width:70px']) . '</td>';

    //echo Html::a($image, ['assegnaricompensaview', 'name' => 'CIAO', 'value' =>$path]);

    $columnCount++;

    if ($columnCount % 6 == 0) {
        echo '</tr><tr>';
    }
}



echo '</tr>';

echo '</table>';
echo Html::endForm();
