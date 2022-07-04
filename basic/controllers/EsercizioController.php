<?php

namespace app\controllers;

use app\models\ComposizioneserieModel;
use app\models\EsercizioModel;
use app\models\FacadeAccount;
use app\models\FacadeEsercizio;
use app\models\ImmagineEsercizioModel;
use yii\data\SqlDataProvider;
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

                if ($isSaved) {
                    return $this->render(
                        'creaesercizioview',
                        [
                            'tipologiaEsercizio' => $tipologiaEsercizio,
                            'nomeEsercizio' => $nomeEsercizio,
                            'model' => new ImmagineEsercizioModel(),
                        ]
                    );
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'Nome esercizio già esistente!');
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
        } else if ($tipologiaEsercizio == 'par') {

            if (!empty($post)) {

                $nomeEsercizio = $facadeEsercizio->getNomeEsercizio($post);

                $isSaved = $facadeEsercizio->salvaEsercizio(
                    $nomeEsercizio, // nome esercizio
                    $tipologiaEsercizio, // tipologia
                    "esercizi/$nomeEsercizio",
                    $post['ImmagineEsercizioModel']['nomeImmagine'], // testo esercizio (null se è esercizio di abbinamento)
                    Yii::$app->user->getId() // email logopedista
                );

                if ($isSaved) {
                    Yii::$app->getSession()->setFlash('success', 'Inserimento andato a buon fine!');
                    $facadeEsercizio->aggiungiImmagineDaForm();
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'Inserimento fallito!');
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

        return $this->render(
            'creaesercizioview',
            [
                'tipologiaEsercizio' => $tipologiaEsercizio,
                'nomeEsercizio' => null,
                'model' => new ImmagineEsercizioModel(),
            ]
        );
    }

    public function actionVisualizzaeserciziview($nomeSerie = 'def')
    {
        $this->layout = 'dashlog'; // carica il layout del logopedista
        $facade = new FacadeEsercizio();
        $postField = 'selection';
        Yii::error(Yii::$app->request->post());

        if (Yii::$app->request->post('ricercaEsercizi') !== null) {
            $post = Yii::$app->request->post();
            $nomeEsercizio = $post['EsercizioModel']['nome'];
            return $this->render(
                'visualizzaeserciziview',
                [
                    'dataProvider' => $facade->getEsercizioByNome($nomeEsercizio),
                ]
            );
        }

        if ($nomeSerie != 'def' && !empty(Yii::$app->request->post($postField))) {
            Yii::error(Yii::$app->request->post($postField));

            if ($facade->aggiungiEserciziToSerie(Yii::$app->request->post($postField), $nomeSerie)) {
                Yii::$app->getSession()->setFlash('success', 'Inserimento andato a buon fine!');
            }
        }

        return $this->render(
            'visualizzaeserciziview',
            [
                'dataProvider' => $facade->getAllEsercizi(),
            ]
        );
    }

    public function actionCreaserieview()
    {

        /*$var = scandir('esercizi/Ciao');
        Yii::error($var);

        Yii::error(substr($var[2], 0, -4));*/

        $this->layout = 'dashlog'; // carica il layout del logopedista
        $post = Yii::$app->request->post();
        $facade = new FacadeEsercizio();
        if ($facade->salvaSerieEsercizi($post)) {
            return $this->redirect('visualizzaeserciziview?nomeSerie=' . $post['SerieModel']['nomeSerie']);
        } else {
            Yii::$app->getSession()->setFlash('danger', 'Nome serie già presente!');
        }
        return $this->render('creaserieview', ['model' => new SerieModel()]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionAssegnaserieview()
    {
        $this->layout = 'dashlog'; // carica il layout del logopedista
        $post = Yii::$app->request->post();
        $facade = new FacadeEsercizio();
        if (Yii::$app->request->post('assegnaSerie') !== null) {
            Yii::error($post['UtenteModel']['username']);
            Yii::error($post['SerieModel']['nomeSerie']);
            Yii::error(date('Y-m-d'));
            $nomeSerie = $post['SerieModel']['nomeSerie'];
            $username = $post['UtenteModel']['username'];
            if ($facade->assegnaSerieEserciziToUtente($username, $nomeSerie)) {
                Yii::$app->getSession()
                    ->setFlash('success', 'La serie ' . $nomeSerie . ' è stata assegnata a: ' . $username);
            } else {
                Yii::$app->getSession()
                    ->setFlash('danger', 'Impossibile assegnare la serie in quanto già precedentemente assegnata');
            }
        }
        return $this->render('assegnaserieview', [
            'modelUtente' => new UtenteModel(),
            'modelSerie' => new SerieModel(),
            'utenti' => $facade->getAllUtenti(),
            'serie' => $facade->getAllSerie()
        ]);
    }



    public function actionListaserieassegnateview()
    {
        $this->layout = 'dashutn';

        $facade = new FacadeEsercizio();

        $string = substr($_COOKIE['utente'], 0, -10);


        return $this->render('listaserieassegnateview', [
            'dataProvider' => $facade->getSerieByUtente(substr($_COOKIE['utente'], 0, -10)),
        ]);
    }

    public function actionSvolgimentoserieview($nomeSerie, $index = 0)
    {

        $this->layout = 'dashutn';

        $facade = new FacadeEsercizio();

        $esercizi = $facade->getAllEserciziBySerie($nomeSerie);

        Yii::error($esercizi[$index]['esercizio']);

        $pathEsercizio = 'esercizi/'.$esercizi[$index]['esercizio'];

        $directory = scandir($pathEsercizio);

        $offset = 2;

        $pathnames = [];
        $soluzioni = [];

        for ($i = $offset; $i < sizeof($directory); $i++) {
            $pathnames[$i-$offset] = '@web/'.$pathEsercizio.'/'.$directory[$i];
            $soluzioni[$i-$offset] = (substr($directory[$i], 0, -4));
        }

        return $this->render('svolgimentoserieview',[
            'model' => new ComposizioneserieModel(),
            'tipologia' => $facade->getTipologiaEsercizio($esercizi[$index]['esercizio']),
            'soluzioni' => $soluzioni,
            'pathnames' => $pathnames
        ]);
    }
}
