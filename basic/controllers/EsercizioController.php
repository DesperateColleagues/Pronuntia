<?php

namespace app\controllers;

use app\models\EsercizioModel;
use app\models\FacadeAccount;
use app\models\FacadeEsercizio;
use app\models\ImmagineEsercizioModel;
use yii\web\UploadedFile;
use Yii;
use yii\helpers\VarDumper;

class EsercizioController extends \yii\web\Controller
{

    private $facadeEsercizio;

    public function actionCreaesercizio($tipologiaEsercizio = 'abb')
    {
        $path = realpath("esercizi");
        $this->facadeEsercizio = new FacadeEsercizio();

        if (!$path || !is_dir($path)) {
            mkdir($directory = "esercizi", $recursive = true);
        }

        $this->layout = 'dashlog';

        $post = Yii::$app->request->post();

        Yii::error($post);

        if ($tipologiaEsercizio == 'let') {

            if (!empty($post)) {
                $isSaved = $this->facadeEsercizio->salvaEsercizio(
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
                'creaesercizio',
                [
                    'tipologiaEsercizio' => $tipologiaEsercizio,
                    'nomeEsercizio' => null,
                    'model' => null,
                ]
            );

        } else if ($tipologiaEsercizio == 'abb') {

            // istanzia il model e restituisce il nome dell'esercizio
            $nomeEsercizio = $this->facadeEsercizio->getNomeEsercizio($post);

            if (isset($post['confirm-button'])) {
                // crea l'esercizio sul database attraverso la facade senza accedere direttamente al model
               $isSaved = $this->facadeEsercizio->salvaEsercizio(
                        $nomeEsercizio, // nome esercizio
                        $tipologiaEsercizio, // tipologia
                        "esercizi/$nomeEsercizio", // path esercizio di abbinamento
                        null, // testo esercizio (null se è esercizio di abbinamento)
                        Yii::$app->user->getId() // email logopedista
                    );

               if ($isSaved){
                    return $this->render(
                        'creaesercizio',
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
                $this->facadeEsercizio->aggiungiImmagine();

                return $this->render(
                    'creaesercizio',
                    [
                        'tipologiaEsercizio' => $tipologiaEsercizio,
                        'nomeEsercizio' => $nomeEsercizio,
                        'model' => new ImmagineEsercizioModel(),
                    ]
                );
            }
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
