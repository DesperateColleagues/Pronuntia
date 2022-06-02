<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "appuntamento".
 *
 * @property string $dataAppuntamento
 * @property string $oraAppuntamento
 * @property string $logopedista
 * @property string|null $utente
 * @property string|null $caregiver
 *
 */
class AppuntamentoModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appuntamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dataAppuntamento', 'oraAppuntamento', 'logopedista'], 'required'],
            [['dataAppuntamento', 'oraAppuntamento'], 'safe'],
            [['logopedista', 'utente', 'caregiver'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dataAppuntamento' => 'Data AppuntamentoModel',
            'oraAppuntamento' => 'Ora AppuntamentoModel',
            'logopedista' => 'Logopedista',
            'utente' => 'Utente',
            'caregiver' => 'Caregiver',
        ];
    }
}
