<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;
    public $tipoUtente; // indica il tipo di utente che si sta registrando

    private $_user = false;

    // scenario: concatenazione di stringhe -> si chiama logcar lo scenario
    const SCENARIO_LOGOPEDISTA_CAREGIVER = TipoAttore::LOGOPEDISTA.TipoAttore::CAREGIVER;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // email, password e tipoUtente sono richiesti obbligatoriamente
            [['email', 'password','tipoUtente'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['email', 'email', 'on' => self::SCENARIO_LOGOPEDISTA_CAREGIVER],
            // password is validated by validatePassword()
            ['password', 'validatePassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email o Username',
            'password' => 'Password'
        ];
    }

    /**
     * Metodo che effettua il login di un utente: qualsiasi classi che implementa IdentityInterface
     * @return bool indica se il login ha avuto successo o meno
    */
    public function login() {
        if ($this->validate()){
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }

        return false;
    }

    public function getUser(){
        //se "l'utente" non è inizializzato, allora dobbiamo recuperare i dati
        if ($this->_user === false) {
            try{
                if ($this->tipoUtente == TipoAttore::LOGOPEDISTA) {
                    $this->_user = LogopedistaModel::findByEmail($this->email);
                } else if ($this->tipoUtente == TipoAttore::UTENTE) {
                    $this->_user = UtenteModel::findByUsername($this->email);
                } else if ($this->tipoUtente == TipoAttore::CAREGIVER) {
                    $this->_user = CaregiverModel::findByEmail($this->email);
                }
            } catch(Exception $e) {
                Yii::error($e->getMessage());
            }

            if ($this->_user == null)
                $this->addError('Email', 'Email non corretta');
        }

        return $this->_user;
    }

    public function validatePassword($attribute, $params){
        if (!$this->hasErrors()){
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)){
                $this->addError('password', 'Password non corretta');
            }
        }
    }

}
