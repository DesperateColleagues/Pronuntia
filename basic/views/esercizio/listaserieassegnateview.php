<?php

use app\models\SerieModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scegli serie da svolgere';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="esercizio-model-listaserieassegnateview">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php try {

        Yii::error($dataProvider->key);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'nomeSerie',
                'dataAssegnazione',
                [
                    'class' => ActionColumn::className(),
                    'visibleButtons' => [
                        'delete' => false,
                        'update' => false
                    ],
                    'urlCreator' => function ($action, SerieModel $model, $key, $index, $column) {
                        $url = $action;

                        if ($action == 'view') {
                            $url = 'svolgimentoserieview';
                        }
                        return Url::toRoute([$url, 'nomeSerie' => $model->nomeSerie]);
                    }
                ],
            ],
        ]);
    } catch (Exception $e) {
        echo '<br><h4> Non sono state trovate serie di esercizi da svolgere. </h4>';
    }
    ?>


    <p>
        <?php echo Html::a('Torna alla dashboard', ['/utente/dashboardutente'], ['class' => 'btn btn-outline-secondary']);
        ?>
    </p>


</div>