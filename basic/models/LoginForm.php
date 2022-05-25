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
    public $tipoUtente;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password','tipoUtente'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // TODO controllare la email
            // password is validated by validatePassword()
            ['password', 'validatePassword']
        ];
    }

    public function login() {
        if ($this->validate()){
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }

        return false;
    }

    public function getUser(){
        if ($this->_user === false){ //se "l'utente" non è inizializzato, allora dobbiamo recuperarci i dati

            try{

                if ($this->tipoUtente == 'log') {//logopedista
                    $this->_user = LogopedistaModel::findByEmail($this->email);
                } else if ($this->tipoUtente == 'utn') {
                    $this->_user = UtenteModel::findByUsername($this->email);
                } else if ($this->tipoUtente == 'car') {
                    $this->_user = CaregiverModel::findByEmail($this->email);
                }

            } catch(Exception $e) {
                Yii::error("Errore, hai molto probabilmente selezionato l'attore sbagliato nell'accesso ".$e->getMessage());
            }
        }

        return $this->_user;
    }

    public function validatePassword($attribute, $params){
        if (!$this->hasErrors()){
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)){
                $this->addError($attribute, 'Email e password non corretti');
            }
        }
    }

}
