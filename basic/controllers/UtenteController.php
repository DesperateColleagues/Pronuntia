<?php

namespace app\controllers;

class UtenteController extends \yii\web\Controller
{
    public function actionDashboardutente()
    {
        if (isset($_COOKIE['utente'])){
            \Yii::error($_COOKIE['utente']);
        }

        $this->layout = 'dashutn';
        return $this->render('dashboardutente');
    }

}
