<?php

namespace app\models;


use Exception;
use Yii;
use yii\base\Model;

class FacadeAccount
{
    private $tipoAttore;
    private $loginForm = null;

    /**
     * @param string $tipoAttore
     */
    public function setTipoAttore($tipoAttore)
    {
        $this->tipoAttore = $tipoAttore;
    }

    /**
     * Questo metodo permette di istanziare un model a seconda del tipo di attore
     * @return Model $model
    */
    private function istanziaModel(){
        if ($this->tipoAttore == 'log')
            $model = new LogopedistaModel();
        else if ($this->tipoAttore == 'car')
            $model = new CaregiverModel();
        else
            $model = new UtenteModel();

        return $model;
    }

    /**
     * Effettua la registrazione al sistema di un attore.
     *
     * @param array $regParam:  array associativo con i parametri della registrazione. Solitamente corrisponde a
     *                          $_POST[] array
     *
     * @return bool             indica se la registrazione è andata a buon fine
     * @throws Exception
     */
    public function registrazione($regParam) {
        $model = $this->istanziaModel();

            if ($model->load($regParam))
                $model->passwordD = md5($model->passwordD);

        return $model->insert();
    }

    /**
     * Effettua l'accesso al sistema di un attore.
     *
     * @param $logParam:   array associativo con i parametri dell'accesso. Solitamente corrisponde a
     *                     $_POST[] array
     *
     * @return string:     restituisce una stringa con il valore 'n' se il login NON va a buon fine,
     *                     altrimenti restituisce il valore del tipo di attore che sta effettuando l'accesso.
     *
    */
    public function accesso($logParam){
        $this->loginForm = new LoginForm();

        if ($this->loginForm->load($logParam)) {
            $utente = $this->loginForm->tipoUtente;

            // setta lo scenario se e solo se il tipo di attore è un logopedista o un caregiver
            if ($utente == TipoAttore::LOGOPEDISTA || $utente == TipoAttore::CAREGIVER)
                $this->loginForm->scenario = TipoAttore::LOGOPEDISTA.TipoAttore::CAREGIVER;

            if ($this->loginForm->login())
                return $utente;
            else
                return 'n';
        }
        return 'n';
    }

    /**
     * @return mixed
     */
    public function getLoginForm()
    {
        if ($this->loginForm == null)
            return new LoginForm();
        else
            return $this->loginForm;
    }

}