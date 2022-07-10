<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

use app\models\AppuntamentoModelSearch;
use yii\grid\GridView;
use yii\helpers\Html;
?>

<div class="form-group">
    <?php
    echo "<h2>Piacere di rivederti, " . $_COOKIE['caregiver'] . "</h2>";
    $searchModel = new AppuntamentoModelSearch();
    $dataProvider = $searchModel->searchCaregiverAppuntamenti($_COOKIE['caregiver']);
    echo "<br>";
    ?>

    <?php
    if ($dataProvider->getCount() > 0) {
        echo "<h5>Prossimi appuntamenti per i tuoi assistiti:</h5>";
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => null,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'dataAppuntamento',
                'oraAppuntamento',
                'logopedista',
                'utente',
                'caregiver',
            ],
        ]);
    } else
        echo "<h5>Non sono presenti prossimi appuntamenti per i tuoi assistiti.</h5>";
    ?>

</div>

<div class="row">
    <div class="col text-center">
        <h4>Prenota una nuova visita</h4>

        <?php echo Html::a('Prenota visita', ['/appuntamento/visualizzaappuntamentiview?tipoAttore=car'], ['class' => 'btn btn-outline-primary']); ?>

    </div>

    <div class="col text-center">
        <h4>Feedback</h4>

        <?php echo Html::a('Feedback', ['/site/about'], ['class' => 'btn btn-outline-primary']); ?>

    </div>
</div>

<div class="text-center">
    <h4>Monitoraggio svolgimento esercizi</h4>
    <?php echo Html::a('Monitora i tuoi utenti', ['/esercizio/monitoraggioeserciziview?tipoAttore=car'], ['class' => 'btn btn-outline-primary']); ?>
</div>