<?php

namespace app\controllers;

use app\models\EsercizioModel;
use app\models\ImmagineEsercizioModel;
use Yii;
use yii\helpers\VarDumper;

class EsercizioController extends \yii\web\Controller
{
    public function actionCreaesercizio($tipologiaEsercizio = 'abb')
    {
        $this->layout = 'dashlog';

        $post = Yii::$app->request->post();

        $iem = new ImmagineEsercizioModel();

        $iem->load($post);

        //Yii::error($iem->attributes);

        if (isset($post['continue-button'])) {

            // TODO salva le immagini
            return $this->render(
                'creaesercizio',
                [
                    'tipologiaEsercizio' => $tipologiaEsercizio,
                    'nomeEsercizio' => $iem->nomeEsercizio,
                    'model' => new ImmagineEsercizioModel(),
                ]
            );
        } else if(isset($post['end-button'])) {

        }

        return $this->render(
            'creaesercizio',
            [
                'tipologiaEsercizio' => $tipologiaEsercizio,
                'nomeEsercizio' => null,
                'model' => new ImmagineEsercizioModel(),
            ]
        );
    }
}
