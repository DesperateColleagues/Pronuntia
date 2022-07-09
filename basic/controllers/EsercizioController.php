<?php

namespace app\controllers;

use app\models\FacadeEsercizio;
use app\models\ImmagineEsercizioModel;
use app\models\SerieModel;
use app\models\UtenteModel;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use Yii;

class EsercizioController extends \yii\web\Controller
{

    public function actionCreaesercizioview($tipologiaEsercizio = 'abb')
    {
        $this->layout = 'dashlog'; // carica il layout del logopedista
        $facadeEsercizio = new FacadeEsercizio();

        // crea la directory esercizi
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
                    'nPic' => NULL
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
                            'nPic' => NULL
                        ]
                    );
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'Nome esercizio già esistente!');
                }
            } else if (isset($post['continue-button'])) {
                $facadeEsercizio->aggiungiImmagineDaForm();

                Yii::$app->getSession()->setFlash('success', 'Inserimento completato');

                $nPic = sizeof(scandir("esercizi/$nomeEsercizio")) - 2;

                return $this->render(
                    'creaesercizioview',
                    [
                        'tipologiaEsercizio' => $tipologiaEsercizio,
                        'nomeEsercizio' => $nomeEsercizio,
                        'model' => new ImmagineEsercizioModel(),
                        'nPic' => $nPic
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
                    'nPic' => NULL
                ]
            );
        }

        return $this->render(
            'creaesercizioview',
            [
                'tipologiaEsercizio' => $tipologiaEsercizio,
                'nomeEsercizio' => null,
                'model' => new ImmagineEsercizioModel(),
                'nPic' => NULL
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
        $this->layout = 'dashlog'; // carica il layout del logopedista
        $post = Yii::$app->request->post();
        $facade = new FacadeEsercizio();
        if ($facade->salvaSerieEsercizi($post)) {
            return $this->redirect('visualizzaeserciziview?nomeSerie=' . $post['SerieModel']['nomeSerie']);
        } else if (!empty($post)) {
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

        return $this->render('listaserieassegnateview', [
            'dataProvider' => $facade->getSerieByUtente($_COOKIE['utente']),
        ]);
    }

    public function actionSvolgimentoserieview($nomeSerie, $index = 0)
    {
        $this->layout = 'dashutn';

        $facade = new FacadeEsercizio();
        $esercizi = $facade->getAllEserciziBySerie($nomeSerie);
        $tipoEs = $facade->getTipologiaEsercizio($esercizi[$index]['esercizio']);
        $nomeEsercizio = $esercizi[$index]['esercizio'];
        $pathEsercizio = 'esercizi/' . $nomeEsercizio;

        $directory = scandir($pathEsercizio);
        $offset = 2;

        $pathnames = [];
        $soluzioni = [];

        for ($i = $offset; $i < sizeof($directory); $i++) {
            $pathnames[$i - $offset] = '@web/' . $pathEsercizio . '/' . $directory[$i];
            $soluzioni[$i - $offset] = (substr($directory[$i], 0, -4));
        }

        $shuffleSoluzioni = [];

        $shuffleSoluzioni = array_merge(array(), $soluzioni); // clonazione array soluzioni
        shuffle($shuffleSoluzioni);

        if ($tipoEs == 'abb') {
            $risposte = preg_split('{,}', Yii::$app->request->post('sort_list_1'));
            $esResult = $this->checkSoluzioni($soluzioni, $risposte);

            if (!empty(Yii::$app->request->post())) {
                if (!$esResult) {
                    Yii::$app->getSession()
                        ->setFlash('danger', 'Risposta sbagliata! Bambino sei una testa di cazzo');

                    $facade->incrementaTentativiEsercizio($nomeEsercizio, $nomeSerie);
                } else {
                    if ($index < sizeof($esercizi) - 1) {
                        $url = Url::toRoute(['svolgimentoserieview', 'nomeSerie' => $nomeSerie, 'index' => $index + 1]);
                    } else {
                        $url = Url::toRoute(['utente/dashboardutente']);
                    }

                    $this->redirect($url);
                }
            }
        } else if ($tipoEs == 'par'){
            if (Yii::$app->request->post('confermaRispostaLettura') !== null){

                if ($index < sizeof($esercizi) - 1) {
                    $url = Url::toRoute(['svolgimentoserieview', 'nomeSerie' => $nomeSerie, 'index' => $index + 1]);
                } else {
                    $url = Url::toRoute(['utente/dashboardutente']);
                }

                $this->redirect($url);
            }
        }

        return $this->render('svolgimentoserieview', [
            'tipologiaEsercizio' => $tipoEs,
            'soluzioni' => $shuffleSoluzioni,
            'pathnames' => $pathnames
        ]);
    }

    private function checkSoluzioni($soluzioni, $risposte){
        for ($i = 0; $i < sizeof($soluzioni); $i++){
            if ($soluzioni[$i] != $risposte[$i])
                return false;
        }

        return true;
    }
}
