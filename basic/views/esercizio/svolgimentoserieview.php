<?php

use app\models\ComposizioneserieModel;
use kartik\sortinput\SortableInput;
use yii\helpers\Html;

/* @var $model ComposizioneserieModel */
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

/*echo '<tr>';

foreach ($soluzioni as $parola) {
    echo '<td>'.Html::input('text','prova',$parola).'</td>';
}
echo '</tr>';*/

echo '</table>';
echo Html::beginForm();
$items = [];
$values = [];
for ($i = 0; $i < sizeof($soluzioni); $i++){
    $values[$i] = ['content' => '<i class="fas fa-cog"></i>'.$soluzioni[$i]];
}
$items = array_fill_keys($soluzioni, $values);
Yii::error($soluzioni);
echo SortableInput::widget([
    'name'=> 'sort_list_1',
    'items' => $items,
    'hideInput' => false,
    'options' => [
        'class' => 'form-control','readonly' => true
    ]
]);

echo '<br>';
echo Html::submitButton('Fine esercizio', ['class' => 'btn btn-primary mr-2',  'name' => 'confermaRisposte']);
echo Html::endForm();
