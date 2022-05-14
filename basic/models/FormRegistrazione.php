<?php

namespace app\models;

use Yii;
use yii\base\Model;

class FormRegistrazione extends Model
{
    public $nome;
    public $cognome;
    public $dataNascita;
    public $email;
    public $password;
    public $idLogopedista;

    private $logopedista;

    const SCENARIO_LOGOPEDISTA = 'logopedista';
    const SCENARIO_UTENTE_AUTONOMO = 'utente_autonomo';
    const SCENARIO_UTENTE_CAREGIVER = 'utente_caregiver';

    public function attributeLabels()
    {
        return [
            'nome' => \Yii::t('app', 'Nome'),
            'cognome' => \Yii::t('app', 'Cognome'),
            'dataNascita' => \Yii::t('app', 'Data di nascita'),
            'email' => \Yii::t('app', 'Email'),
            'password' => \Yii::t('app', 'Password')
        ];
    }

    public function rules()
    {
        return [
            [['nome', 'cognome', 'dataNascita', 'email', 'password'], 'required'],
            ['email', 'email'],
        ];
    }

    public function registraLogopedista(){
        $sql = "INSERT INTO logopedista (nome, cognome, dataNascita, email, passwordD) 
                    VALUES ('".$this->nome."','".$this->cognome."','".$this->dataNascita."',
                    '".$this->email."','".md5($this->password)."')";

        $columnCount = Yii::$app->db->createCommand($sql)->execute();

        if ($columnCount > 0)
            return true;
        else
            return false;
    }
}