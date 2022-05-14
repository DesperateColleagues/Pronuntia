<?php

namespace app\models;

abstract class AbstractPersona {
    private $nome;
    private $cognome;
    private $dataNascita;
    private $email;
    private $password;

    public function __construct($nome, $cognome, $dataNascita, $email, $password){
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->dataNascita = $dataNascita;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @return mixed
     */
    public function getCognome()
    {
        return $this->cognome;
    }

    /**
     * @return mixed
     */
    public function getDataNascita()
    {
        return $this->dataNascita;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
}