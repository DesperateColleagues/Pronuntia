<?php

namespace app\controllers;

use app\models\CaregiverModel;
use app\models\LoginForm;
use app\models\FormRegistrazione;
use Yii;
use yii\web\Controller;

class LogopedistaController extends Controller
{
    //private $account;

    public function actionRegistrazione($tipoAttore = 'log'){
        try {
                $account = new FacadeAccount();
                $account->setTipoAttore($tipoAttore);
                // esegue l'inserimento dati nel database sfruttando l'active record
                $res = $account->registrazione(Yii::$app->request->post());

                if ($res) {
                    if ($tipoAttore == 'log') {
                        return $this->render('@app/views/site/index', [
                            'model' => new LoginForm()
                        ]);
                    } else if ($tipoAttore == 'car') {
                        $this->layout = 'plain';
                        $tipoAttore = 'utn';
                        $email = Yii::$app->request->post('CaregiverModel')['email'];
                        return $this->render('registrazione', [ 'caregiverEmail' => $email,
                            'attore' => $tipoAttore,
                        ]);
                    } else {
                        $this->layout = 'base';
                        return $this->render('@app/views/logopedista/dashboardlogopedista', [
                        ]); /*$this->actionDashboardlogopedista();*/
                    }
                } else {
                    Yii::error($res);
                }
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }

        // default view rendering
            return $this->render('registrazione', [
                'attore' => $tipoAttore, 'caregiverEmail' => null,
            ]);
    }

    public function actionDashboardlogopedista () {
        return $this->render('dashboardlogopedista');
    }

}