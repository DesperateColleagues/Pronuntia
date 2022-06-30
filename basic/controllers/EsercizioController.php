<?php

namespace app\controllers;

use app\models\EsercizioModel;
use app\models\FacadeAccount;
use app\models\FacadeEsercizio;
use app\models\ImmagineEsercizioModel;
use app\models\SerieModel;
use yii\web\UploadedFile;
use Yii;
use yii\helpers\VarDumper;

class EsercizioController extends \yii\web\Controller
{

    public function actionCreaesercizioview($tipologiaEsercizio = 'abb')
    {
        $this->layout = 'dashlog'; // carica il layout del logopedista
        $facadeEsercizio = new FacadeEsercizio();

        // crea la directory esercizi
        // todo: spostare nel costruttore di facade
        $path = realpath("esercizi");

        if (!$path || !is_dir($path)) {
            mkdir($directory = "esercizi", $recursive = true);
        }

        $post = Yii::$app->request->post(); // recupera il post

        if ($tipologiaEsercizio == 'let') {

            if (!empty($post)) {
                $isSaved = $facadeEsercizio->salvaEsercizio(
                    $post['EsercizioModel']['nome'], // nome esercizio
                    $tipologiaEsercizio, // tipologia
                    null,
                    $post['EsercizioModel']['testo'], // testo esercizio (null se è esercizio di abbinamento)
                    Yii::$app->user->getId() // email logopedista
                );

                if ($isSaved) {
                    Yii::error("Model salvato");
                }
            }

            return $this->render(
                'creaesercizioview',
                [
                    'tipologiaEsercizio' => $tipologiaEsercizio,
                    'nomeEsercizio' => null,
                    'model' => null,
                ]
            );

        } else if ($tipologiaEsercizio == 'abb') {

            // istanzia il model e restituisce il nome dell'esercizio
            $nomeEsercizio = $facadeEsercizio->getNomeEsercizio($post);

            if (isset($post['confirm-button'])) {
                // crea l'esercizio sul database attraverso la facade senza accedere direttamente al model
               $isSaved = $facadeEsercizio->salvaEsercizio(
                        $nomeEsercizio, // nome esercizio
                        $tipologiaEsercizio, // tipologia
                        "esercizi/$nomeEsercizio", // path esercizio di abbinamento
                        null, // testo esercizio (null se è esercizio di abbinamento)
                        Yii::$app->user->getId() // email logopedista
                    );

               if ($isSaved){
                    return $this->render(
                        'creaesercizioview',
                        [
                            'tipologiaEsercizio' => $tipologiaEsercizio,
                            'nomeEsercizio' => $nomeEsercizio,
                            'model' => new ImmagineEsercizioModel(),
                        ]
                    );
                } else {
                    // todo: alert nome esercizio esistente
                    Yii::error("None esercizio già esistente");
                }

            } else if (isset($post['continue-button'])) {
                $facadeEsercizio->aggiungiImmagineDaForm();

                return $this->render(
                    'creaesercizioview',
                    [
                        'tipologiaEsercizio' => $tipologiaEsercizio,
                        'nomeEsercizio' => $nomeEsercizio,
                        'model' => new ImmagineEsercizioModel(),
                    ]
                );
            }
        }

        return $this->render(
            'creaesercizioview',
            [
                'tipologiaEsercizio' => $tipologiaEsercizio,
                'nomeEsercizio' => null,
                'model' => new ImmagineEsercizioModel(),
            ]
        );
    }

    public function actionVisualizzaeserciziview($nomeSerie = 'def'){
        $this->layout = 'dashlog'; // carica il layout del logopedista
        $facade = new FacadeEsercizio();
        $postField = 'selection';

        if ($nomeSerie != 'def' && !empty(Yii::$app->request->post($postField))) {
            Yii::error(Yii::$app->request->post($postField));
            Yii::error($facade->aggiungiEserciziToSerie(Yii::$app->request->post($postField), $nomeSerie));
        }

        return $this->render('visualizzaeserciziview', [
                'dataProviderAbb' => $facade->getAllEserciziByTipo('abb'),
                'dataProviderText' => $facade->getAllEserciziByTipo('let')
            ]
        );
    }

    public function actionCreaserieview(){
        $this->layout = 'dashlog'; // carica il layout del logopedista
        $post = Yii::$app->request->post();
        $facade = new FacadeEsercizio();
        if ($facade->salvaSerieEsercizi($post)){
            return $this->redirect('visualizzaeserciziview?nomeSerie='.$post['SerieModel']['nomeSerie']);
        } else {
            Yii::error("Serie non create");
        }
        return $this->render('creaserieview', ['model' => new SerieModel()]);
    }
}
