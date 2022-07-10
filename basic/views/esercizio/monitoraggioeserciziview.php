<?php

/** @var yii\web\View $this */
/** @var $tipoAttore string */
/** @var $utenti array() */
/** @var $serie array() */
/** @var $esercizi array() */

use app\models\TipoAttore;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
?>

<h1> Monitoraggio esercizi </h1>

<div class="form-group">
    <?php

    $tipoAttore = $_GET['tipoAttore'];

    echo Html::beginForm();

    if ($tipoAttore == TipoAttore::LOGOPEDISTA) {

        if ($utenti == NULL && $esercizi == NULL) {

            $serie = $serie->getModels();

            echo '<div>' . "Monitoraggio utenti di " . Yii::$app->user->getId() . '</div>';

            echo "<br>";

            $serie = ArrayHelper::map($serie, 'nomeSerie', function ($par) {
                return $par['nomeSerie'] . ' - Assegnata il: ' . $par['dataAssegnazione'];
            });

            echo '<div>' . Html::dropDownList('listaSerie', null, $serie) . '</div>';

            echo "<br>";

            echo Html::submitButton('Continua', ['class' => 'btn btn-primary mr-1',  'name' => 'setSerie']);
        } else if ($esercizi == NULL && $serie == NULL) {

            echo '<div>' . "Monitoraggio utenti di " . Yii::$app->user->getId() . '</div>';

            echo "<br>";

            $utenti = ArrayHelper::map($utenti, 'username', function ($par) {
                return $par['nome'] . ' ' . $par['cognome'] . ' - ' . $par['username'];
            });

            echo '<div>' . Html::dropDownList('listaUtenti', null, $utenti) . '</div>';

            echo "<br>";

            echo Html::a('Torna alla dashboard', ['/logopedista/dashboardlogopedista?tipoAttore=log'], ['class' => 'btn btn-outline-secondary mr-2']);

            echo Html::submitButton('Continua', ['class' => 'btn btn-primary mr-1',  'name' => 'setUser']);
        } else if ($utenti == NULL && $serie == NULL) {

            echo GridView::widget([
                'dataProvider' => $esercizi,
                'filterModel' => null,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'serie',
                    'esercizio',
                    'esito',
                    'tentativi',
                ],
            ]);
        }

        echo Html::endForm();
    } else if ($tipoAttore == TipoAttore::CAREGIVER) {
        echo "Monitoraggio assistiti di " . $_COOKIE['caregiver'];

        echo "<br>";

        echo Html::a('Torna alla dashboard', ['/caregiver/dashboardcaregiver'], ['class' => 'btn btn-outline-secondary mr-2']);
    }
    ?>

</div>