<?php

namespace app\controllers;

use app\models\CaregiverModel;
use app\models\LogopedistaModel;
use app\models\UtenteModel;
use Exception;
use Yii;
use yii\base\Model;

class FacadeAccount
{
    private $tipoAttore;

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

    public function accesso($logParam){
        // todo: gestire accesso multiplo con identità
    }

}