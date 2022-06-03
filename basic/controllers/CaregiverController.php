<?php

namespace app\controllers;

class CaregiverController extends \yii\web\Controller
{
    public function actionDashboardcaregiver()
    {
        if (isset($_COOKIE['caregiver'])){
            \Yii::error($_COOKIE['caregiver']);
        }

        $this->layout = 'dashcar';
        return $this->render('dashboardcaregiver');
    }

}
