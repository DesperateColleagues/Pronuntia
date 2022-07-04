<?php

use yii\helpers\Html;

/* @var $model yii\models\ComposizioneserieModel */
/* @var $tipologiaEsercizio string */
/* @var $soluzioni array */
/* @var $pathnames array */


echo "<table style = 'margin-left:auto;margin-right:auto'>";
echo '<tr>';

foreach ($pathnames as $path) {

    Yii::error($path);
    echo '<td>'.Html::img($path,['style' => 'width:300px']).'</td>';
}

echo '</tr>';
echo '<tr>';

foreach ($soluzioni as $parola) {
    echo '<td>'.Html::input('text','prova',$parola).'</td>';
}
echo '</tr>';

echo '</table>';
