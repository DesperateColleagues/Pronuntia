<?php

use app\models\CaregiverModel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AppuntamentoModel */

if(isset($_COOKIE['CurrentActor'])) {
    $tipoAttore = $_COOKIE['CurrentActor'];
}

Yii::error($tipoAttore);

$this->title = $model->dataAppuntamento;
$this->params['breadcrumbs'][] = ['label' => 'Appuntamento Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="appuntamento-model-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php

            if ($tipoAttore == \app\models\TipoAttore::LOGOPEDISTA){
                echo Html::a('Update',
                    ['update',
                        'dataAppuntamento' => $model->dataAppuntamento,
                        'oraAppuntamento' => $model->oraAppuntamento,
                        'logopedista' => $model->logopedista],
                    ['class' => 'btn btn-primary']);

                echo Html::a('Delete',
                    ['delete',
                        'dataAppuntamento' => $model->dataAppuntamento,
                        'oraAppuntamento' => $model->oraAppuntamento,
                        'logopedista' => $model->logopedista],
                    [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]);
            } else {
                $form = ActiveForm::begin(['id' => 'confirm-form']);

                if (isset($_COOKIE['caregiver'])){
                    $caregiverMail = $_COOKIE['caregiver'];
                    $modelCaregiver = new CaregiverModel();
                    $username = $modelCaregiver->getUserByEmail($caregiverMail);

                    echo $form->field($model, 'caregiver')
                        ->hiddenInput(['value' => $caregiverMail])->label(false);

                    echo $form->field($model, 'utente')
                        ->hiddenInput(['value' => $username])->label(false);
                } else if (isset($_COOKIE['utente'])){
                    $username = $_COOKIE['utente'];

                    echo $form->field($model, 'utente')
                        ->hiddenInput(['value' => $username])->label(false);
                }

                echo '<div class="form-group">';
                echo Html::submitButton('Conferma', ['class' => 'btn btn-primary', 'name' => 'confirm-button']);
                echo '</div>';

                ActiveForm::end();
            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dataAppuntamento',
            'oraAppuntamento',
            'logopedista',
            'utente',
            'caregiver',
        ],
    ]) ?>

</div>
