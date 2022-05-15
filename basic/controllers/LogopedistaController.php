<?php

namespace app\controllers;

use app\models\LogopedistaModel;
use Yii;
use yii\web\Controller;

class LogopedistaController extends Controller
{
    public function actionRegistrazione(){
        $model = new LogopedistaModel();

        try {
            if ($model->load(Yii::$app->request->post())) {
                // cripta la password con md5
                $model->passwordD = md5($model->passwordD);

                // esegue l'inserimento dati nel database sfruttando l'active record
                if ( $model->insert()) {
                    return $this->render('@app/views/logopedista/dashboardlogopedista', [
                        'message' => $model->nome . ' ' . $model->cognome
                    ]);
                }
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }

        // view rendering
        return $this->render('registrazione', [
            'model' => $model
        ]);
    }
}