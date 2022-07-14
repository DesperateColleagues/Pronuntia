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

        if ($utenti == NULL && $esercizi == NULL && $serie != NULL) {


            $serie = $serie->getModels();

            if ($serie != NULL) {

                echo '<div><h5>' . "Monitoraggio utenti di " . Yii::$app->user->getId() . '</h5></div>';

                echo "<br>";

                $serie = ArrayHelper::map($serie, 'nomeSerie', function ($par) {
                    return $par['nomeSerie'] . ' - Assegnata il: ' . $par['dataAssegnazione'];
                });

                echo '<div>' . Html::dropDownList('listaSerie', null, $serie) . '</div>';

                echo "<br>";

                echo Html::submitButton('Continua', ['class' => 'btn btn-primary mr-1',  'name' => 'setSerie']);
            } else {
                echo '<div>Attualmente non risultano presenti serie assegnate a questo utente.</div>';
                echo "<br>";
            }
        } else if ($esercizi == NULL && $serie == NULL) {

            echo '<div><h5>' . "Monitoraggio utenti di " . Yii::$app->user->getId() . '</h5></div>';

            echo "<br>";

            if ($utenti != NULL) {

                $utenti = ArrayHelper::map($utenti, 'username', function ($par) {
                    return $par['nome'] . ' ' . $par['cognome'] . ' - ' . $par['username'];
                });

                echo '<div>' . Html::dropDownList('listaUtenti', null, $utenti) . '</div>';

                echo "<br>";

                echo Html::submitButton('Continua', ['class' => 'btn btn-primary mr-1',  'name' => 'setUser']);
            } else {

                echo '<div>Attualmente non risultano utenti a lei associati.</div>';
                echo "<br>";
            }
        } else if ($esercizi != NULL && $serie == NULL) {

            echo GridView::widget([
                'dataProvider' => $esercizi,
                'filterModel' => null,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'serie',
                    'esercizio',
                    [
                        'attribute' => 'Esito',
                        'filter' => false,
                        'format' => 'raw',
                        'value' => function ($esercizi) {
                            if ($esercizi->esito > 0)
                                return "Superato";
                            else
                                return "Non superato";
                        },
                    ],
                    'tentativi',
                ],
            ]);
        }
        echo Html::a('Torna alla dashboard', ['/logopedista/dashboardlogopedista?tipoAttore=log'], ['class' => 'btn btn-outline-secondary mr-2']);

        echo Html::endForm();
    } else if ($tipoAttore == TipoAttore::CAREGIVER) {

        echo "<br>";

        if ($utenti == NULL && $esercizi == NULL && $serie != NULL) {

            $serie = $serie->getModels();

            if ($serie != NULL) {

                echo '<div><h5>' . "Monitoraggio assistiti di " . $_COOKIE['caregiver'] . '</h5></div>';

                echo "<br>";

                $serie = ArrayHelper::map($serie, 'nomeSerie', function ($par) {
                    return $par['nomeSerie'] . ' - Assegnata il: ' . $par['dataAssegnazione'];
                });

                echo '<div>' . Html::dropDownList('listaSerie', null, $serie) . '</div>';

                echo "<br>";

                echo Html::submitButton('Continua', ['class' => 'btn btn-primary mr-1',  'name' => 'setSerie']);
            } else {
                echo '<div>Attualmente non risultano presenti serie assegnate ai suoi assistiti.</div>';
                echo "<br>";
            }
        } else {

            echo GridView::widget([
                'dataProvider' => $esercizi,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'serie',
                    'esercizio',
                    [
                        'attribute' => 'Esito',
                        'filter' => false,
                        'format' => 'raw',
                        'value' => function ($esercizi) {
                            if ($esercizi->esito > 0)
                                return "Superato";
                            else
                                return "Non superato";
                        },
                    ],
                    'tentativi',
                ],
            ]);
        }
        echo Html::a('Torna alla dashboard', ['/caregiver/dashboardcaregiver'], ['class' => 'btn btn-outline-secondary mr-2']);
    }
    ?>

</div>