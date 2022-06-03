<?php

use app\models\AppuntamentoModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AppuntamentoModelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Appuntamenti';
$this->params['breadcrumbs'][] = $this->title;
$tipoAttore = $_GET['tipoAttore'];

$cookie_name = "CurrentActor";
$cookie_value = $tipoAttore;
setcookie($cookie_name, $cookie_value, 0, "/"); // 86400 = 1 day

$isDeleteVisible = true;

if ($tipoAttore == \app\models\TipoAttore::CAREGIVER)
    $isDeleteVisible = false;
?>
<div class="appuntamento-model-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
            if ($tipoAttore == \app\models\TipoAttore::LOGOPEDISTA)
                echo Html::a('Aggiungi appuntamento', ['create'], ['class' => 'btn btn-success']);
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dataAppuntamento',
            'oraAppuntamento',
            'logopedista',
            'utente',
            'caregiver',
            [
                'class' => ActionColumn::className(),
                'visibleButtons' => [
                        'delete' => $isDeleteVisible,
                        'update' => $isDeleteVisible
                ],
                'urlCreator' => function ($action, AppuntamentoModel $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'dataAppuntamento' => $model->dataAppuntamento, 'oraAppuntamento' => $model->oraAppuntamento, 'logopedista' => $model->logopedista]);
                 }
            ],
        ],
    ]); ?>


</div>
