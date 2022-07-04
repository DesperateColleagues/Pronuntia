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
<div class="appuntamento-model-visualizzaappuntamentiview">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function ($model) {
                if ($model->utente == null) {
                    return ['class' => 'danger'];
                } else {
                    return ['class' => 'success'];
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
                        $url = $action;

                        if ($action == 'view') {
                            $url = 'dettagliappuntamento';
                        } else if ($action == 'update') {
                            $url = 'aggiornaappuntamento';
                        } else if ($action == 'delete') {
                            $url = 'delete';
                        }
                        return Url::toRoute([$url, 'dataAppuntamento' => $model->dataAppuntamento, 'oraAppuntamento' => $model->oraAppuntamento, 'logopedista' => $model->logopedista]);
                    }
                ],
            ],
        ]);
    } catch (Exception $e) {
        echo '<h2> Non sono stati trovati appuntamenti con i parametri inseriti </h2>';
        echo Html::a('Visualizza Appuntamento', ['visualizzaappuntamentiview?tipoAttore='.$tipoAttore], ['class' => 'btn btn-primary mb-1']); //mr-1 specifica i punti di margine da lasciare a destra del bottone. (proprietà margin CSS)
    }
    ?>

    
<p>
        <?php
            if ($tipoAttore == \app\models\TipoAttore::LOGOPEDISTA) {
                echo Html::a('Aggiungi appuntamento', ['creaappuntamento'], ['class' => 'btn btn-primary mr-1']); //mr-1 specifica i punti di margine da lasciare a destra del bottone. (proprietà margin CSS)
                echo Html::a('Visualizza diagnosi inserite', ['visualizzadiagnosi'], ['class' => 'btn btn-primary mr-1']);
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
