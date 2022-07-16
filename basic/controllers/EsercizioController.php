<?php

namespace app\controllers;

use app\models\FacadeEsercizio;
use app\models\ImmagineEsercizioModel;
use app\models\SerieModel;
use app\models\TipoAttore;
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

        /**
         * Si controlla se l'end-button è settato, al di fuori della ramificazione if-else principale
         * in quanto esso è presente in un'altro form. Questo form si attiva all'interno della creazione di esercizi di
         * abbinamento. Pertanto il bottone verrà settato SOLTANTO AL TERMINE della creazione di un esercizio di
         * abbinamento.
         *
         * Questo controllo è dovuto al fatto che è necessario controllare il momento preciso in cui
         * si inserisce la tupla di esercizio di abbinamento nel DB.
        */
        if (isset($post['end-button'])) {
            $nomeEsercizio = $_COOKIE['nomeEsercizio'];
            // salva l'esercizio di abbinamento sul database
            $isSaved = $facadeEsercizio->salvaEsercizio(
                $nomeEsercizio, // nome esercizio
                $tipologiaEsercizio, // tipologia
                "esercizi/$nomeEsercizio", // path esercizio di abbinamento
                null, // testo esercizio (null se è esercizio di abbinamento)
                Yii::$app->user->getId() // email logopedista
            );

            if (!$isSaved) {
                Yii::$app->getSession()->setFlash('danger',
                    'Sono stati commessi errori nella procedura di inserimento');
            }

        } else { // flusso dic controllo principale
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
                        'nPic' => null
                    ]
                );
            } else if ($tipologiaEsercizio == 'abb') {

                if (!empty($post)) {
                    // recupera il nome dell'esercizio
                    $nomeEsercizio = $post['ImmagineEsercizioModel']['nomeEsercizio'];

                    if (isset($post['confirm-button'])) {
                        // aggiorna la view di creazione esercizi
                        if ($facadeEsercizio->checkNomeEsercizio($nomeEsercizio)) {
                            // crea la directory dove memorizzare gli esercizi
                            $facadeEsercizio->creaDirectory($nomeEsercizio);
                            /*  setta un cookie che contiene il nome dell'esercizio. Questo valore verrà recuperato
                                solo se è settato end-buttton*/
                            setcookie('nomeEsercizio', $nomeEsercizio, 0, "/");

                            return $this->render(
                                'creaesercizioview',
                                [
                                    'tipologiaEsercizio' => $tipologiaEsercizio,
                                    'nomeEsercizio' => $nomeEsercizio,
                                    'model' => new ImmagineEsercizioModel(),
                                    'nPic' => null
                                ]
                            );
                        } else {
                            Yii::$app->getSession()->setFlash('danger',
                                'Nome esercizio esistente: impossibile continuare');
                        }
                    } else if (isset($post['continue-button'])) {
                        Yii::$app->getSession()->setFlash('success', 'Inserimento completato');
                        $facadeEsercizio->aggiungiImmagineDaForm($post); // aggiunge l'immagine dal form nella cartella

                        // recupera il numero di immagini inserite dalla cartella
                        $nPic = sizeof(scandir("esercizi/$nomeEsercizio")) - 2;

                        // se si arriva al numero massimo di immagini caricabili, viene effettuato il salvataggio su db
                        if ($nPic == 4){
                            $facadeEsercizio->salvaEsercizio(
                                $nomeEsercizio, // nome esercizio
                                $tipologiaEsercizio, // tipologia
                                "esercizi/$nomeEsercizio", // path esercizio di abbinamento
                                null, // testo esercizio (null se è esercizio di abbinamento)
                                Yii::$app->user->getId() // email logopedista
                            );
                        }

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
                }
            } else if ($tipologiaEsercizio == 'par') {

                if (!empty($post)) {
                    $nomeEsercizio = $post['ImmagineEsercizioModel']['nomeEsercizio'];

                    if ($facadeEsercizio->checkNomeEsercizio($nomeEsercizio)) {
                        $isSaved = $facadeEsercizio->salvaEsercizio(
                            $nomeEsercizio, // nome esercizio
                            $tipologiaEsercizio, // tipologia
                            "esercizi/$nomeEsercizio",
                            $post['ImmagineEsercizioModel']['nomeImmagine'], // il testo corrisponde al nome dell'immagine
                            Yii::$app->user->getId() // email logopedista
                        );

                        if ($isSaved) {
                            Yii::$app->getSession()->setFlash('success', 'Inserimento andato a buon fine!');
                            // crea la directory dove memorizzare gli esercizi
                            $facadeEsercizio->creaDirectory($nomeEsercizio);
                            // aggiunge l'immagine dal form nella cartella
                            $facadeEsercizio->aggiungiImmagineDaForm($post);
                        } else {
                            Yii::$app->getSession()->setFlash('danger', 'Inserimento fallito!');
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('danger',
                            'Nome esercizio esistente: impossibile continuare');
                    }
                }
            }
        }

        // render di default
        return $this->render(
            'creaesercizioview',
            [
                'tipologiaEsercizio' => $tipologiaEsercizio,
                'nomeEsercizio' => null,
                'model' => new ImmagineEsercizioModel(),
                'nPic' => null
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
        $esercizi = $facade->getAllEserciziBySerie($nomeSerie, 'a');
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

        $post = Yii::$app->request->post();
        if (isset($post['saltaRisposta'])) {
            if ($index < sizeof($esercizi) - 1) {
                $url = Url::toRoute(['svolgimentoserieview', 'nomeSerie' => $nomeSerie, 'index' => $index + 1]);
            } else {
                $url = Url::toRoute(['esercizio/classificaview']);
            }
            $this->redirect($url);
            $facade->setEsitoEsercizio($nomeEsercizio, $nomeSerie, false);
        }

        if ($tipoEs == 'abb') {
            $risposte = preg_split('{,}', Yii::$app->request->post('sort_list_1'));
            $esResult = $this->checkSoluzioni($soluzioni, $risposte);

            if (!empty(Yii::$app->request->post())) {
                if (!$esResult) {
                    Yii::$app->getSession()
                        ->setFlash('danger', 'Risposta sbagliata! Riprova');

                    $facade->incrementaTentativiEsercizio($nomeEsercizio, $nomeSerie);

                    $facade->setEsitoEsercizio($nomeEsercizio, $nomeSerie, false);
                } else {
                    if ($index < sizeof($esercizi) - 1) {
                        $url = Url::toRoute(['svolgimentoserieview', 'nomeSerie' => $nomeSerie, 'index' => $index + 1]);
                    } else {
                        $url = Url::toRoute(['esercizio/classificaview']);
                    }
                    $facade->setEsitoEsercizio($nomeEsercizio, $nomeSerie, true);

                    $this->redirect($url);
                }
            }
        } else if ($tipoEs == 'par') {
            if (Yii::$app->request->post('confermaRispostaLettura') !== null) {

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

    public function actionClassificaview()
    {
        $facade = new FacadeEsercizio();

        return $this->render('classificaview', [
            'entries' => $facade->generaClassifica()
        ]);
    }

    public function actionMonitoraggioeserciziview($tipoAttore)
    {
        $facade = new FacadeEsercizio();

        $post = Yii::$app->request->post();

        if ($tipoAttore == TipoAttore::LOGOPEDISTA) {

            $utenti = $facade->getAllUtenti();

            if (isset($post['setUser'])) {
                Yii::warning($post);

                $serie = $facade->getSerieByUtente($post['listaUtenti']);

                return $this->render('monitoraggioeserciziview', [
                    'tipoAttore' => 'log',
                    'utenti' => NULL,
                    'serie' => $serie,
                    'esercizi' => NULL
                ]);
            } else if (isset($post['setSerie'])) {
                Yii::warning($post);

                $nomeSerie = $post['listaSerie'];

                $esercizi = $facade->getAllEserciziBySerie($nomeSerie, 'd');

                Yii::warning($esercizi);

                return $this->render('monitoraggioeserciziview', [
                    'tipoAttore' => 'log',
                    'utenti' => NULL,
                    'serie' => NULL,
                    'esercizi' => $esercizi
                ]);
            }
            return $this->render('monitoraggioeserciziview', [
                'tipoAttore' => 'log',
                'utenti' => $utenti,
                'serie' => NULL,
                'esercizi' => NULL
            ]);
        } else if ($tipoAttore == TipoAttore::CAREGIVER) {

            if (isset($post['setSerie'])) {

                $nomeSerie = $post['listaSerie'];

                $esercizi = $facade->getAllEserciziBySerie($nomeSerie, 'd');

                return $this->render('monitoraggioeserciziview', [
                    'tipoAttore' => 'car',
                    'utenti' => NULL,
                    'serie' => NULL,
                    'esercizi' => $esercizi
                ]);
            } else {

                if (isset($_COOKIE['caregiver'])) { // recupero dati in presenza di un caregiver
                    $caregiverMail = $_COOKIE['caregiver'];

                    $username = $facade->getUtenteByCaregiver($caregiverMail);

                    $serie = $facade->getSerieByUtente($username);
                }

                return $this->render('monitoraggioeserciziview', [
                    'tipoAttore' => 'car',
                    'utenti' => NULL,
                    'serie' => $serie,
                    'esercizi' => NULL
                ]);
            }
        }
    }

    private function checkSoluzioni($soluzioni, $risposte)
    {
        for ($i = 0; $i < sizeof($soluzioni); $i++) {
            if ($soluzioni[$i] != $risposte[$i])
                return false;
        }

        return true;
    }
}
