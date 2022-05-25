<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class UtenteModel extends ActiveRecord implements IdentityInterface
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
            [['email'], 'email'],
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
    public function getCaregiverModel()
    {
        return $this->hasOne(CaregiverModel::className(), ['email' => 'caregiver']);
    }

    /**
     * Gets query for [[Logopedista0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogopedistaModel()
    {
        return $this->hasOne(LogopedistaModel::className(), ['email' => 'logopedista']);
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

    public static function findByUsername($username){
        return self::findOne(['username' => $username]);
    }

    public function validatePassword($password){
        return $this->passwordD == md5($password);
    }

}
