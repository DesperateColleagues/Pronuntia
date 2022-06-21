<?php

namespace app\controllers;

use app\models\EsercizioModel;
use app\models\ImmagineEsercizioModel;
use yii\web\UploadedFile;
use Yii;
use yii\helpers\VarDumper;

class EsercizioController extends \yii\web\Controller
{
    public function actionCreaesercizio($tipologiaEsercizio = 'abb')
    {
        $this->layout = 'dashlog';

        $post = Yii::$app->request->post();

        Yii::error($post);

        if ($tipologiaEsercizio == 'let') {

            $model = new EsercizioModel();

            if ($model->load($post)) {

                Yii::error($model->attributes);

                $model->save();
            }


            return $this->render(
                'creaesercizio',
                [
                    'tipologiaEsercizio' => $tipologiaEsercizio
                ]
            );
        } else if ($tipologiaEsercizio == 'abb') {

            $path = realpath("esercizi");

            if ($path == false || !is_dir($path)) {
                mkdir($directory = "esercizi", $recursive = true);
            }


            $iem = new ImmagineEsercizioModel();

            $iem->load($post);

            //Yii::error($iem->attributes);

            if (isset($post['continue-button'])) {

                $path = realpath("esercizi/$iem->nomeEsercizio");

                if ($path == false || !is_dir($path)) {
                    mkdir("esercizi/$iem->nomeEsercizio");
                } else {
                }

                $iem->file = UploadedFile::getInstance($iem, 'file');
                $iem->file->saveAs("esercizi/$iem->nomeEsercizio/$iem->nomeImmagine.jpg");

                return $this->render(
                    'creaesercizio',
                    [
                        'tipologiaEsercizio' => $tipologiaEsercizio,
                        'nomeEsercizio' => $iem->nomeEsercizio,
                        'model' => new ImmagineEsercizioModel(),
                    ]
                );
            } else if (isset($post['end-button'])) {

                $path = realpath("esercizi/$iem->nomeEsercizio");

                if ($path == false || !is_dir($path)) {
                    mkdir("esercizi/$iem->nomeEsercizio");
                }

                $iem->file = UploadedFile::getInstance($iem, 'file');
                $iem->file->saveAs("esercizi/$iem->nomeEsercizio/$iem->nomeImmagine.jpg");

                $model = new EsercizioModel();
                $model->nome = $iem->nomeEsercizio;
                $model->tipologia = $tipologiaEsercizio;
                $model->path = "esercizi/$iem->nomeEsercizio";
                $model->logopedista = Yii::$app->user->getId();

                $model->save();


                //return $this->redirect('logopedista/dashboardlogopedista?tipoAttore=log');
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
}
