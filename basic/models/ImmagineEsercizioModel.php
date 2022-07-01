<?php

namespace app\models;

use Yii;

class ImmagineEsercizioModel extends \yii\base\Model
{

    public $nomeEsercizio;
    public $nomeImmagine;
    public $file;

    public function rules()
    {
        return [
            [['nomeEsercizio','nomeImmagine','file'], 'required']
        ];
    }
}