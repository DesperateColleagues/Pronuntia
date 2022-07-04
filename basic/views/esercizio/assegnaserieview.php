<?php

/** @var array $utenti */
/** @var array $serie */
/** @var UtenteModel $modelUtente */
/** @var SerieModel $modelSerie*/

use app\models\SerieModel;
use app\models\UtenteModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

<h1>Assegnazione serie</h1>

<?php
$form = \yii\widgets\ActiveForm::begin();

$utenti = ArrayHelper::map($utenti, 'username', function ($par) {
    return $par['nome'] . ' ' . $par['cognome'] . ' - ' . $par['username'];
});

echo $form->field($modelUtente, 'username')->dropDownList(
    $utenti
);

$serie = ArrayHelper::map($serie, 'nomeSerie', function ($par) {
    $message = $par['nomeSerie'];
    if ($par['utente'] != null) {
        $message = $message . ' - assegnata a: ' . $par['utente'];
    } else {
        $message = $message . ' - non assegnata';
    }
    return $message;
});


echo $form->field($modelSerie, 'nomeSerie')->dropDownList(
    $serie
);

echo Html::submitButton('Assegna', ['class' => 'btn btn-primary mr-1',  'name' => 'assegnaSerie']);
echo Html::a('Torna alla dashboard', ['/logopedista/dashboardlogopedista?tipoAttore=log'], ['class' => 'btn btn-outline-secondary']);

$form::end();
?>