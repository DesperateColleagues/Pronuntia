<?php
/** @var yii\web\View $this */
/* @var $dataProviderAbb yii\data\ActiveDataProvider */
/* @var $dataProviderText yii\data\ActiveDataProvider */

use yii\grid\ActionColumn;
use yii\helpers\Html;
?>
<?php

?>
<?php

echo Html::beginForm();
/**
 * @throws Exception
 */
function gridviewCustom($data){
    return \yii\grid\GridView::widget([
        'dataProvider' => $data,
        'id' => 'grid',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            'tipologia',
            'logopedista',
            ['class' => 'yii\grid\CheckboxColumn'],
        ],
]);
}

echo '<h3>Esercizi abbinamento</h3>';

try {
    echo gridviewCustom($dataProviderAbb);
} catch (Exception $e) {
    Yii::error($e->getMessage());
}

echo '<h3>Esercizi lettura</h3>';

try {
    echo gridviewCustom($dataProviderText);
} catch (Exception $e) {
    Yii::error($e->getMessage());
}

echo Html::submitButton('Submittami tutto', ['class' => 'btn btn-primary mr-1',  'name' => 'selezionaEsercizi']);
echo Html::endForm();

// NON MODIFICARE SENNO' NON FUNZIONA UN CAZZO
$script = "$(function(){
    $('#selezionaEsercizi').click(function (){
        $.post(['selezionati', {
                pk: $('#grid').yiiGridView('getSelectedRows')
            }])
        })
    }";
// HO DETTO NON RIMUOVERE FIGLIO DI PUTTANA
$this ->registerJs($script);
// SE RIMUOVI QUESTO SCRIPT VENGO A CERCARTI
?>

