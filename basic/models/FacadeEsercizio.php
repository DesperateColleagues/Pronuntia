<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

class FacadeEsercizio
{
    private $iem;

    /**
     * Istanzia il model del form e restituisce il nome dell'esercizio
     *
     * @param array $imgModelPar array post
     *
     * @return string nome dell'esercizio
    */
    public function getNomeEsercizio($imgModelPar){
        $this->iem = new ImmagineEsercizioModel();
        $this->iem->load($imgModelPar);

        return $this->iem->nomeEsercizio;
    }

    /**
     * Aggiunge un immagine per l'esercizio di abbinamento
     *
    */
    public function aggiungiImmagineDaForm(){
        $file = UploadedFile::getInstance($this->iem, 'file');
        $file->saveAs('esercizi/'.$this->iem->nomeEsercizio.'/'. $this->iem->nomeImmagine. '.jpg');
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
    public function salvaEsercizio($nome, $tipologiaEsercizio, $path, $testo, $logopedista){

        // salva il record solo se la directory viene creata
        if ($this->creaDirectory($nome)) {
            $model = new EsercizioModel();

            $model->nome = $nome;
            $model->tipologia = $tipologiaEsercizio;
            $model->path = $path;
            $model->testo = $testo;
            $model->logopedista = $logopedista;

            return $model->save();
        } else {
            return false;
        }
    }

    /**
     * Restituisce tutti gli esercizi inseriti nel DB
     *
     * @param string $tipo tipologia di esercizio
     *
     * @return ActiveDataProvider
    */
    public function getAllEserciziByTipo($tipo){
        return new ActiveDataProvider([
            'query' => EsercizioModel::find()->where(['tipologia' => $tipo]),
        ]);
    }

    /**
     * Restituisce la tupla dell'esercizio corrispondente al nome passato in input
     *
     * @param string $nome nome dell'esercizio
     *
     * @return ActiveDataProvider
    */
    public function getEsercizioByNome($nome){
        return new ActiveDataProvider([
            'query' => EsercizioModel::find()->where(['nome' => $nome]),
            ]
        );
    }

    /**
     * Salva su database la serie
     *
     * @param array $serieParams
     * @return bool
    */
    public function salvaSerieEsercizi($serieParams){
        $model = new SerieModel();
        $model->load($serieParams);
        return $model->save();
    }

    /**
     * Aggiunge gli esercizi alla serie e la salva su database
     *
     * @param array $esercizi  array contenente gli esercizi da aggiungere alla serie
     * @param string $nomeSerie nome della serie
     *
     * @return bool risultato dell'inserimento
    */
    public function aggiungiEserciziToSerie($esercizi, $nomeSerie){
        $ret = true;

        for ($i = 0; $i < sizeof($esercizi); $i++){
            $model = new ComposizioneserieModel();
            $model->esercizio = $esercizi[$i];
            $model->serie = $nomeSerie;

            \Yii::error( $model->serie);
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
    private function creaDirectory($nomeDir){
        $path = realpath("esercizi/$nomeDir");
        $ret = false;

        if (!$path || !is_dir($path)) {
            mkdir("esercizi/$nomeDir");
            $ret = true;
        }

        return $ret;
    }

}