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


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model){ //dovrebbe colorare di rosso o di verde le tuple ma non funziona, lol
            if ($model->utente == null) {
                return ['class'=>'danger'];
            } else {
                return ['class'=>'success'];
            }
        },
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

    
<p>
        <?php
            if ($tipoAttore == \app\models\TipoAttore::LOGOPEDISTA) {
                echo Html::a('Aggiungi appuntamento', ['create'], ['class' => 'btn btn-primary mr-1']); //mr-1 specifica i punti di margine da lasciare a destra del bottone. (proprietà margin CSS)
                echo Html::a('Visualizza diagnosi inserite', ['show'], ['class' => 'btn btn-primary mr-1']);
                echo Html::a('Torna alla dashboard', ['/logopedista/dashboardlogopedista?tipoAttore='.$tipoAttore], ['class' => 'btn btn-outline-secondary']);
                //bottone di redirect alla dashboard del logopedista
            } else if ($tipoAttore == \app\models\TipoAttore::CAREGIVER) {
                echo Html::a('Torna alla dashboard', ['/caregiver/dashboardcaregiver?tipoAttore='.$tipoAttore], ['class' => 'btn btn-outline-secondary']);
            } else if ($tipoAttore == \app\models\TipoAttore::UTENTE_NON_AUTONOMO) {
                echo Html::a('Torna alla dashboard', ['/utente/dashboardutente'], ['class' => 'btn btn-outline-secondary']);
            }
        ?>
    </p>


</div>
