<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "utente".
 *
 * @property string $username
 * @property string $nome
 * @property string $cognome
 * @property string $dataNascita
 * @property string $email
 * @property string $passwordD
 * @property string $logopedista
 * @property string|null $caregiver
 *
 * @property CaregiverModel $caregiverModel
 * @property LogopedistaModel $logopedistaModel
 */
class UtenteModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'nome', 'cognome', 'dataNascita', 'email', 'passwordD', 'logopedista'], 'required'],
            [['dataNascita'], 'safe'],
            [['username', 'nome', 'cognome'], 'string', 'max' => 60],
            [['email', 'passwordD', 'logopedista', 'caregiver'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['logopedista'], 'exist', 'skipOnError' => true, 'targetClass' => LogopedistaModel::className(), 'targetAttribute' => ['logopedista' => 'email']],
            [['caregiver'], 'exist', 'skipOnError' => true, 'targetClass' => CaregiverModel::className(), 'targetAttribute' => ['caregiver' => 'email']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'nome' => 'Nome',
            'cognome' => 'Cognome',
            'dataNascita' => 'Data Nascita',
            'email' => 'Email',
            'passwordD' => 'Password D',
            'logopedista' => 'Logopedista',
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
        return $this->hasOne(CaregiverModel::className(), ['email' => 'caregiver']);
    }

    /**
     * Gets query for [[Logopedista0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogopedista0()
    {
        return $this->hasOne(LogopedistaModel::className(), ['email' => 'logopedista']);
    }
}
