<?php

namespace app\controllers;


use Yii;


class CaregiverController extends \yii\web\Controller
{
    public function actionDashboardcaregiver()
    {
        if (isset($_COOKIE['caregiver'])){
            Yii::error($_COOKIE['caregiver']);
        }

        $this->layout = 'dashcar';
        return $this->render('dashboardcaregiver');
    }

    /*public function actionAssegnaricompensaview(){

        Yii::warning(Yii::$app->request->post('CIAO'));
        $pathEsercizio = 'ricompense';

        $directory = scandir($pathEsercizio);
        $offset = 2;

        $pathnames = [];

        for ($i = $offset; $i < sizeof($directory); $i++) {
            $pathnames[$i - $offset] = '@web/' . $pathEsercizio . '/' . $directory[$i];
        }
        return $this->render('assegnaricompensaview', [
            'pathnames' => $pathnames
        ]);
    }*/

}
