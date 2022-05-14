<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\FormRegistrazione;

class LogopedistaController extends Controller
{
    public function actionRegistrazione(){
        $model = new FormRegistrazione();

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if ($model->registraLogopedista()){
                return $this->render('@app/views/logopedista/dashboardlogopedista', [
                    'message' => $model->nome.' '.$model->cognome
                ]);
            }
        }

        // view rendering
        return $this->render('registrazione', [
            'model' => $model
        ]);
    }
}