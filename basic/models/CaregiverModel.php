<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use Yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class CaregiverModel extends ActiveRecord implements IdentityInterface
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
            [['email'], 'email'],
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

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException();
    }

    public function getId()
    {
        return $this->email;
    }

    public function getAuthKey()
    {
        return null;//$this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException();
        //return $this->authKey == $authKey;
    }

    public static function findByEmail($email){
        return self::findOne(['email' => $email]);
    }

    public function validatePassword($password){
        return $this->passwordD == md5($password);
    }
}
