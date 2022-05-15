<?php

namespace app\models;

class Utente extends AbstractPersona
{
    private $idUtente;

    public function __construct($nome, $cognome, $dataNascita, $email, $password, $idUtente){
        parent::__construct($nome, $cognome, $dataNascita, $email, $password);
        $this->idUtente = $idUtente;
    }

    /**
     * @return mixed
     */
    public function getIdUtente()
    {
        return $this->idUtente;
    }
}