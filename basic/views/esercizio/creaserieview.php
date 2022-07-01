<?php
/* @var $this yii\web\View */
/* @var $model SerieModel*/

use app\models\SerieModel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<h1>Creazione serie</h1>

<?php

$form = ActiveForm::begin(['id' => 'inizia-form-nuovo-esercizio']);
echo $form->field($model, 'nomeSerie')->textInput();
echo $hiddenFieldLogopedista = $form->field($model, 'logopedista')
    ->hiddenInput(['value' => Yii::$app->user->getId()])->label(false);
echo Html::submitButton('Crea serie', ['class' => 'btn btn-primary mr-1',  'name' => 'creaSerie']);
echo Html::a('Torna alla dashboard', ['/logopedista/dashboardlogopedista?tipoAttore='.'log'], ['class' => 'btn btn-outline-secondary']);

ActiveForm::end();