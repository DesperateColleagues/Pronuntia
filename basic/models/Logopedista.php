<?php

namespace app\models;

class Logopedista extends AbstractPersona
{
    private $idLogopedista;

    public function __construct($nome, $cognome, $dataNascita, $email, $password, $idLogopedista){
        parent::__construct($nome, $cognome, $dataNascita, $email, $password);
        $this->idLogopedista = $idLogopedista;
    }

    /**
     * @return mixed
     */
    public function getIdLogopedista()
    {
        return $this->idLogopedista;
    }

}