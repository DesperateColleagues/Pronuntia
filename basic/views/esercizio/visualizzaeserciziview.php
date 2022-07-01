<?php
use app\models\EsercizioModelSearch;
use yii\helpers\Html;

/** @var yii\web\View $this */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php

?>
<?php

echo '<h3>Ricerca esercizio</h3>';
$form = \yii\widgets\ActiveForm::begin();
echo $form->field(new \app\models\EsercizioModel(), 'nome')->textInput()->label("Nome esercizio");
echo Html::submitButton('Ricerca', ['class' => 'btn btn-primary mr-1',  'name' => 'ricercaEsercizi']);
$form::end();
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

echo '<br>';
echo '<h3>Lista esercizi</h3>';

try {
    echo gridviewCustom($dataProvider);
} catch (Exception $e) {
    Yii::error($e->getMessage());
}


echo Html::submitButton('Conferma inserimento', ['class' => 'btn btn-primary mr-2',  'name' => 'selezionaEsercizi']);
echo Html::a('Torna a crea serie', ['/esercizio/creaserieview'], ['class' => 'btn btn-outline-secondary mr-2']);
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

