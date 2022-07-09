<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
use app\models\AppuntamentoModelSearch;
use yii\grid\GridView;
use yii\helpers\Html;
?>

<h1> Benvenuto </h1>

<div class="form-group">
    <?php
    echo "<br>Dati del caregiver loggato: ".$_COOKIE['caregiver'];
    $searchModel = new AppuntamentoModelSearch();
    $dataProvider = $searchModel->searchCaregiverAppuntamenti($_COOKIE['caregiver']);
    ?>

    <?= GridView::widget([
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
    ]); ?>

</div>
