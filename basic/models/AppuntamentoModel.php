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
 * @property Caregiver $caregiver0
 * @property Logopedista $logopedista0
 * @property Utente $utente0
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
            [['logopedista', 'utente', 'caregiver'], 'string', 'max' => 255],
            [['logopedista'], 'exist', 'skipOnError' => true, 'targetClass' => Logopedista::className(), 'targetAttribute' => ['logopedista' => 'email']],
            [['caregiver'], 'exist', 'skipOnError' => true, 'targetClass' => Caregiver::className(), 'targetAttribute' => ['caregiver' => 'email']],
            [['utente'], 'exist', 'skipOnError' => true, 'targetClass' => Utente::className(), 'targetAttribute' => ['utente' => 'username']],
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

    /**
     * Gets query for [[Caregiver0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCaregiver0()
    {
        return $this->hasOne(Caregiver::className(), ['email' => 'caregiver']);
    }

    /**
     * Gets query for [[Logopedista0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogopedista0()
    {
        return $this->hasOne(Logopedista::className(), ['email' => 'logopedista']);
    }

    /**
     * Gets query for [[Utente0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUtente0()
    {
        return $this->hasOne(Utente::className(), ['username' => 'utente']);
    }

    public static function findByPK($data, $log, $time){
        return self::findOne(['dataAppuntamento' => $data,'logopedista' => $log, 'oraAppuntamento' => $time]);
    }
}
