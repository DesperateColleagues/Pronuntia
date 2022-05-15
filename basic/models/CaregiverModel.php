<?php

namespace app\models;

use Yii;
use Yii\db\ActiveRecord;
/**
 * This is the model class for table "caregiver".
 *
 * @property string $nome
 * @property string $cognome
 * @property string $dataNascita
 * @property string $email
 * @property string $passwordD
 *
 * @property UtenteModel[] $utenti
 */
class CaregiverModel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'caregiver';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'cognome', 'dataNascita', 'email', 'passwordD'], 'required'],
            [['dataNascita'], 'safe'],
            [['nome', 'cognome'], 'string', 'max' => 60],
            [['email', 'passwordD'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'cognome' => 'Cognome',
            'dataNascita' => 'Data Nascita',
            'email' => 'Email',
            'passwordD' => 'Password D',
        ];
    }

    /**
     * Gets query for [[Utentes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUtenti()
    {
        return $this->hasMany(UtenteModel::className(), ['caregiver' => 'email']);
    }
}
