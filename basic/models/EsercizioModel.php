<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "esercizio".
 *
 * @property string $nome
 * @property string|null $path
 * @property string $tipologia
 * @property string|null $testo
 * @property string $logopedista
 */
class EsercizioModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'esercizio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'tipologia', 'logopedista'], 'required'],
            [['testo'], 'string'],
            [['nome', 'path', 'tipologia', 'logopedista'], 'string', 'max' => 255],
            [['nome'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'path' => 'Path',
            'tipologia' => 'Tipologia',
            'testo' => 'Testo',
            'logopedista' => 'Logopedista',
        ];
    }

}
