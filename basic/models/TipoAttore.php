<?php

namespace app\models;
abstract class TipoAttore
{
    const LOGOPEDISTA = 'log';
    const CAREGIVER = 'car';
    const UTENTE = 'ut';
    const UTENTE_AUTONOMO = 'uta';
    const UTENTE_NON_AUTONOMO = 'utn';
}