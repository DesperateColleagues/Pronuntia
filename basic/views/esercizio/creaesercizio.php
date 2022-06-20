<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\VarDumper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model EsercizioModel */
/* @var $tipologiaEsercizio */

$this->title = 'Creazione nuovo esercizio';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="esercizio-model-create-new">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php

    //echo $form->field($model, 'nome')->textInput();

    if ($tipologiaEsercizio == 'abb') {
        
    } else if ($tipologiaEsercizio == 'let')
    ?>


    <?php echo Html::a('Torna alla dashboard', ['/logopedista/dashboardlogopedista?tipoAttore=log'], ['class' => 'btn btn-outline-secondary']); ?>


</div>