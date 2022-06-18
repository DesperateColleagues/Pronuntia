<?php

use app\models\AppuntamentoModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AppuntamentoModelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $tipoAttore string*/

$this->title = 'Diagnosi Inserite';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="appuntamento-model-show-diagnosis">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php
    //VarDumper::dump($dataProvider->models);
    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dataAppuntamento',
            'utente',

            // More complex one.
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'delete' => false,
                    'update' => false,
                    'view' => false
            ],
            ],
            [
                'attribute' => 'Scarica diagnosi',
                'format' => 'raw',
                'value' => function ($data) {
                    $path = '/diagnosi/';
                    $filename = 'Diagnosi.'.$data['id'].'.docx';
                    return Html::a('Download', [Yii::$app->urlManager->createUrl(['site/download','path'=>$path,'file'=>$filename])], ['class' => 'btn btn-outline-primary']);
                }
            ],
        ],
    ]); 
    ?>

    <?php echo Html::a('Torna agli appuntamenti',['index','tipoAttore' => $tipoAttore],['class' => 'btn btn-outline-secondary']); ?>


</div>