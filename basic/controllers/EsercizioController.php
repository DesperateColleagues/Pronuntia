<?php

namespace app\controllers;

use app\models\EsercizioModel;
use app\models\ImmagineEsercizioModel;
use Yii;

class EsercizioController extends \yii\web\Controller
{
    public function actionCreaesercizio($tipologiaEsercizio = 'abb')
    {
        $this->layout = 'dashlog';

        $post = Yii::$app->request->post();

        return $this->render('creaesercizio',[
            'tipologiaEsercizio' => $tipologiaEsercizio,
                'nomeEsercizio' => null,
                'model' => new ImmagineEsercizioModel(),
            ]
        );
    }

}
