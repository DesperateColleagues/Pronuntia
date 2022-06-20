<?php

namespace app\controllers;

use Yii;

class EsercizioController extends \yii\web\Controller
{
    public function actionCreaesercizio($tipologiaEsercizio = 'abb')
    {
        Yii::error("PENE");
        $this->layout = 'dashlog';
        return $this->render('creaesercizio', ['tipologiaEsercizio' => $tipologiaEsercizio]);
    }

}
