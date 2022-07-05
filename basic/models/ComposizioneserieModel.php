<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "composizioneserie".
 *
 * @property string $serie
 * @property string $esercizio
 * @property int $tentativi [smallint]
 *
 */
class ComposizioneserieModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'composizioneserie';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serie', 'esercizio'], 'required'],
            [['serie'], 'string', 'max' => 60],
            [['esercizio'], 'string', 'max' => 255],
            //[['serie', 'esercizio'], 'unique', 'targetAttribute' => ['serie', 'esercizio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'serie' => 'SerieModel',
            'esercizio' => 'Esercizio',
        ];
    }
}
