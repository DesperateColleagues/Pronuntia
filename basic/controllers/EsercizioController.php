<?php

namespace app\controllers;

use app\models\EsercizioModel;
use app\models\FacadeAccount;
use app\models\FacadeEsercizio;
use app\models\ImmagineEsercizioModel;
use app\models\SerieModel;
use app\models\UtenteModel;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
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
        Yii::error(Yii::$app->request->post());

        if (Yii::$app->request->post('ricercaEsercizi') !== null){
            $post = Yii::$app->request->post();
            $nomeEsercizio = $post['EsercizioModel']['nome'];
            return $this->render('visualizzaeserciziview', [
                    'dataProvider' => $facade->getEsercizioByNome($nomeEsercizio),
                ]
            );
        }

        if ($nomeSerie != 'def' && !empty(Yii::$app->request->post($postField))) {
            Yii::error(Yii::$app->request->post($postField));

            if ($facade->aggiungiEserciziToSerie(Yii::$app->request->post($postField), $nomeSerie)){
                // todo: inserisci alert
            }

        }

        return $this->render('visualizzaeserciziview', [
                'dataProvider' => $facade->getAllEsercizi(),
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

    public function actionAssegnaserieview(){
        $this->layout = 'dashlog'; // carica il layout del logopedista
        $post = Yii::$app->request->post();
        $facade = new FacadeEsercizio();

        if (Yii::$app->request->post('assegnaSerie') !== null){
            Yii::error($post['UtenteModel']['username']);
            Yii::error($post['SerieModel']['nomeSerie']);
            Yii::error(date('Y-m-d'));
            Yii::error($facade->assegnaSerieEserciziToUtente($post['UtenteModel']['username'], $post['SerieModel']['nomeSerie']));
        }

        return $this->render('assegnaserieview', [
            'modelUtente' => new UtenteModel(),
            'modelSerie' => new SerieModel(),
            'utenti' => $facade->getAllUtenti(),
            'serie' => $facade->getAllSerie()
        ]);
    }


}
