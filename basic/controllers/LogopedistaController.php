<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\LogopedistaModel;
use Yii;
use yii\web\Controller;

class LogopedistaController extends Controller
{

    public function actionRegistrazione($tipoAttore = 'log'){

        try {
                $account = new FacadeAccount($tipoAttore);
                // esegue l'inserimento dati nel database sfruttando l'active record
                $res = $account->registrazione(Yii::$app->request->post());
                if ($res) {
                    Yii::info("Positivo");
                    if ($tipoAttore == 'log') {
                        return $this->render('@app/views/site/login', [
                            'model' => new LoginForm()
                        ]);
                    } else if ($tipoAttore == 'car') {
                        // todo implementazione utente non autonomo
                    } else {
                        $this->layout = 'base';
                        return $this->render('@app/views/logopedista/dashboardlogopedista', [
                            'message' => 'Dashboard logopedista'
                        ]);
                    }
                }
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }

        // view rendering
        return $this->render('registrazione', [
            'attore' => $tipoAttore,
            'email' => null
        ]);
    }

}