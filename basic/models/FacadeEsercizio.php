<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use Yii;

class FacadeEsercizio
{
    private $iem;

    /**
     * Istanzia il model del form e restituisce il nome dell'esercizio
     *
     * @param string $nomeEsercizio nome dell'esercizio
     *
     * @return bool indica se il nome dell'esercizio esiste o meno
     */
    public function checkNomeEsercizio($nomeEsercizio)
    {

        $res = EsercizioModel::findAll(['nome' => $nomeEsercizio]);

        if (empty($res))
            return true;
        else
            return false;
    }

    /**
     * Aggiunge un immagine per l'esercizio di abbinamento
     *
     */
    public function aggiungiImmagineDaForm($post)
    {
        $this->iem = new ImmagineEsercizioModel();
        $this->iem->load($post);

        $file = UploadedFile::getInstance($this->iem, 'file');
        $file->saveAs('esercizi/' . $this->iem->nomeEsercizio . '/' . $this->iem->nomeImmagine . '.jpg');
    }

    /**
     * Questo metodo permette di creare un nuovo esercizio generico.
     *
     * @param string $nome il nome dell'esercizio
     * @param string $tipologiaEsercizio tipologia dell'esercizio 'abb' o 'let'
     * @param string $path percorso fisico sul quale viene memorizzato l'esercizio
     * @param string $testo testo dell'esercizio di lettura
     * @param string $logopedista email del logopedista
     *
     * @return bool indica se la creazione ha avuto successo o meno
     */
    public function salvaEsercizio($nome, $tipologiaEsercizio, $path, $testo, $logopedista)
    {

        $model = new EsercizioModel();

        $model->nome = $nome;
        $model->tipologia = $tipologiaEsercizio;
        $model->path = $path;
        $model->testo = $testo;
        $model->logopedista = $logopedista;

        return $model->save();
    }

    /**
     * Restituisce tutti gli esercizi inseriti nel DB
     *
     * @return ActiveDataProvider
     */
    public function getAllEsercizi()
    {
        return new ActiveDataProvider([
            'query' => EsercizioModel::find()
        ]);
    }

    /**
     * Restituisce la tupla dell'esercizio corrispondente al nome passato in input
     *
     * @param string $nome nome dell'esercizio
     *
     * @return ActiveDataProvider
     */
    public function getEsercizioByNome($nome)
    {
        return new ActiveDataProvider(
            [
                'query' => EsercizioModel::find()->where(['nome' => $nome]),
            ]
        );
    }

    public function getUtenteByCaregiver($caregiverMail){
        $modelCaregiver = new CaregiverModel();
        return $modelCaregiver->getUserByEmail($caregiverMail);
    }

    /**
     * Restituisce le tuple delle serie corrispondenti allo username passato in input
     *
     * @param string $username username dell'utente
     *
     * @return ActiveDataProvider
     */
    public function getSerieByUtente($username)
    {
        return new ActiveDataProvider(
            [
                'query' => SerieModel::find()->where(['utente' => $username]),
            ]
        );
    }

    /**
     * Restituisce la tipologia dell'esercizio passato in input attraverso il suo nome
     *
     * @param string $nomeEsercizio nome esercizio di cui cercare la tipologia
     *
     * @return string
     */
    public function getTipologiaEsercizio($nomeEsercizio)
    {
        $tipoArray = ArrayHelper::toArray(EsercizioModel::find()
            ->select('tipologia')
            ->where(['nome' => $nomeEsercizio])
            ->all());

        return $tipoArray[0]['tipologia'];
    }

    /**
     * Restituisce tutti gli esercizi della serie passata in input tramite il nome
     *
     * @return array|object[]|string[]|ActiveDataProvider
     */
    public function getAllEserciziBySerie($nomeSerie,$mode)
    {
        if($mode == 'a'){
        return ArrayHelper::toArray(ComposizioneserieModel::find()
            ->select('esercizio')
            ->where(['serie' => $nomeSerie])
            ->all());
        } else {
            return new ActiveDataProvider(
                [
                    'query' => ComposizioneserieModel::find()
                        ->where(['serie' => $nomeSerie]),
                ]
            );
        }

    }

    /**
     * Restituisce tutte le serie inserite nel db come array
     *
     * @return array le serie inserite
     */
    public function getAllSerie()
    {
        return ArrayHelper::toArray(SerieModel::find()
            ->where(['logopedista' => \Yii::$app->user->id])
            ->all());
    }

    /**
     * Restituisce tutte gli utenti inserite nel db come array
     *
     * @return array utenti inseriti
     */
    public function getAllUtenti()
    {
        return ArrayHelper::toArray(UtenteModel::find()
            ->where(['logopedista' => \Yii::$app->user->id])
            ->all());
    }

    /**
     * Salva su database la serie
     *
     * @param array $serieParams
     * @return bool
     */
    public function salvaSerieEsercizi($serieParams)
    {
        $model = new SerieModel();
        $model->load($serieParams);
        return $model->save();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function assegnaSerieEserciziToUtente($utente, $nomeSerie)
    {
        $model = $this->findModelSerie($nomeSerie);
        if ($model->utente == null) {
            $model->utente = $utente;
            $model->dataAssegnazione = date('Y-m-d');
            return $model->save();
        }
        return false;
    }

    /**
     * Aggiunge gli esercizi alla serie e la salva su database
     *
     * @param array $esercizi  array contenente gli esercizi da aggiungere alla serie
     * @param string $nomeSerie nome della serie
     *
     * @return bool risultato dell'inserimento
     */
    public function aggiungiEserciziToSerie($esercizi, $nomeSerie)
    {
        $ret = true;

        for ($i = 0; $i < sizeof($esercizi); $i++) {
            $model = new ComposizioneserieModel();
            $model->esercizio = $esercizi[$i];
            $model->serie = $nomeSerie;

            \Yii::error($model->serie);
            \Yii::error($model->esercizio);

            $ret = $model->save();

            if (!$ret)
                break;
        }

        return $ret;
    }

    /**
     * Crea una directory su disco
     *
     * @param string $nomeDir nome della directory
     *
     * @return bool indica se la directory è stata creata o meno
     */
    public function creaDirectory($nomeDir)
    {
        $path = realpath("esercizi/$nomeDir");
        $ret = false;

        if (!$path || !is_dir($path)) {
            mkdir("esercizi/$nomeDir");
            $ret = true;
        }

        return $ret;
    }


    /**
     * Incrementa il numero di tentativi per un determinato esercizio di una serie
     *
     * @param string $nomeEsercizio nome dell'esercizio
     * @param string $nomeSerie nome della serie
     *
     * @return bool
     */
    public function incrementaTentativiEsercizio($nomeEsercizio, $nomeSerie)
    {
        $model = ComposizioneserieModel::findOne(['serie' => $nomeSerie, 'esercizio' => $nomeEsercizio]);
        $model->tentativi = $model->tentativi + 1;
        return $model->save();
    }

    /**
     * Incrementa il numero di tentativi per un determinato esercizio di una serie
     *
     * @param string $nomeEsercizio nome dell'esercizio
     * @param string $nomeSerie nome della serie
     *
     * @return bool
     */
    public function setEsitoEsercizio($nomeEsercizio, $nomeSerie, $value)
    {
        $model = ComposizioneserieModel::findOne(['serie' => $nomeSerie, 'esercizio' => $nomeEsercizio]);
        if ($value)
            $model->esito = 1;
        else
            $model->esito = 0;

        return $model->save();
    }

    /**
     * Restituisce una classifica ordinata
     *
     * @return array elementi della classfica
     */
    public function generaClassifica()
    {
        $username = $_COOKIE['utente'];
        $MAX_PUNTEGGIO = 5000;
        $MIN_PUNTEGGIO = 2000;

        $utenti = array('jak', 'sowimdp31', 'antonioFire85', 'xx_darkAngelCraft_xx', 'PippoPunico');
        shuffle($utenti);
        $utenti[] = $username;

        $classifica = array_fill_keys($utenti, 0);
        $punteggi = array();

        // generazione di punteggi casuali per la classifica
        for ($i = 0; $i < sizeof($utenti) - 1; $i++) {
            $punteggi[$i] = rand($MIN_PUNTEGGIO, $MAX_PUNTEGGIO);
        }

        sort($punteggi);
        // generazione del punteggio utente affinchè sia compreso tra i primi 3
        $punteggioUtente = rand($punteggi[sizeof($punteggi) - 3], $punteggi[sizeof($punteggi) - 1] - 1);

        // serve per non generare un punteggio duplicato
        while (in_array($punteggioUtente, $punteggi)) {
            $punteggioUtente = rand($punteggi[sizeof($punteggi) - 3], $punteggi[sizeof($punteggi) - 1] - 1);
        }

        $punteggi[] = $punteggioUtente; // aggiunta del punteggio dell'utente


        // popola array della classifica
        for ($i = 0; $i < sizeof($utenti); $i++) {
            $classifica[$utenti[$i]] = $punteggi[$i];
        }

        arsort($classifica);
        Yii::error($classifica);

        return $classifica;
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModelSerie($id)
    {
        if (($model = SerieModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
