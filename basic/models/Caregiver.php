<?php

namespace app\models;

class Caregiver extends AbstractPersona
{
    private $idCaregiver;

    public function __construct($nome, $cognome, $dataNascita, $email, $password, $idCaregiver){
        parent::__construct($nome, $cognome, $dataNascita, $email, $password);
        $this->idCaregiver = $idCaregiver;
    }

    /**
     * @return mixed
     */
    public function getIdCaregiver()
    {
        return $this->idCaregiver;
    }

}