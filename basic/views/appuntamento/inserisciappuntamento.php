<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */


use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use kartik\date\DatePicker;

$this->title = 'Inserimento Nuovo Appuntamento';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="form-group">
<h1><?= Html::encode($this->title) ?></h1>

<?php

    $model = new \app\models\AppuntamentoModel();

    $form = ActiveForm::begin([
        'id' => 'appuntamento-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]);

    $logopedistaEmail = Yii::$app->user->getId();
    $hiddenFieldLogopedista = $form->field($model, 'logopedista')
    ->hiddenInput(['value' => $logopedistaEmail])->label(false);

    echo $form->field($model, 'oraAppuntamento')->widget(\janisto\timepicker\TimePicker::className(), [
        'language' => 'it',
        'mode' => 'time',
        'clientOptions' => [
            'timeFormat' => 'HH:mm:ss',
            'showSecond' => false,
        ]
    ]);

    echo $form->field($model, 'dataAppuntamento')->widget(DatePicker::className(), [
        'name' => 'dataAppuntamento',
        'type' => DatePicker::TYPE_INPUT,
        'value' => '',
        'bsVersion' => '4',
        'language' => 'it',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])->label('Data appuntamento');

    echo $hiddenFieldLogopedista;
?>


    <div class="form-group">
        <div class="offset-lg-1 col-lg-11">
            <?= Html::submitButton('Aggiungi appuntamento', ['class' => 'btn btn-primary', 'name' => 'add-appointment-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
