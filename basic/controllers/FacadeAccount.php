<?php

namespace app\controllers;

use app\models\CaregiverModel;
use app\models\LogopedistaModel;
use app\models\UtenteModel;
use Exception;

class FacadeAccount
{
    private $tipoAttore;

    public function __construct($tipoAttore) {
        $this->tipoAttore = $tipoAttore;
    }

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
     * @throws Exception
     */
    public function registrazione($regParam) {
        $model = $this->istanziaModel();

            if ($model->load($regParam))
                $model->passwordD = md5($model->passwordD);

        return $model->insert();
    }

}