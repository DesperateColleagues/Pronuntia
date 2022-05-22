<?php

namespace app\models;

use Yii;
use yii\base\Model;

class FormRegistrazione extends Model
{
    public $logopedista;
    public $utente;
    public $caregiver;

    public function __construct(){
        parent::__construct();
        $this->logopedista = new LogopedistaModel();
    }

    const SCENARIO_LOGOPEDISTA = 'logopedista';
    const SCENARIO_UTENTE_AUTONOMO = 'utente_autonomo';
    const SCENARIO_UTENTE_CAREGIVER = 'utente_caregiver';

    public function registraLogopedista(){
        $sql = "INSERT INTO logopedista (nome, cognome, dataNascita, email, passwordD)
                    VALUES ('".$this->logopedista->nome."','".$this->logopedista->cognome."','".$this->logopedista->dataNascita."',
                    '".$this->logopedista->email."','".md5($this->logopedista->password)."')";

        $columnCount = Yii::$app->db->createCommand($sql)->execute();

        if ($columnCount > 0)
            return true;
        else
            return false;
    }

    public function scenarios()
    {
        $scenarios =  parent::scenarios();

    }

    public function registrazione(){

    }
}