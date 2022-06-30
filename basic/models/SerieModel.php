<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "serie".
 *
 * @property string $nomeSerie
 * @property string $logopedista
 * @property string|null $utente
 * @property string|null $dataAssegnazione
 *
 */
class SerieModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'serie';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nomeSerie', 'logopedista'], 'required'],
            [['dataAssegnazione'], 'safe'],
            [['nomeSerie'], 'string', 'max' => 60],
            [['logopedista', 'utente'], 'string', 'max' => 255],
            [['nomeSerie'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nomeSerie' => 'Nome SerieModel',
            'logopedista' => 'Logopedista',
            'utente' => 'Utente',
            'dataAssegnazione' => 'Data Assegnazione',
        ];
    }
}
