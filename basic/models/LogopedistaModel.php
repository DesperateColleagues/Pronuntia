<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "logopedista".
 *
 * @property string $nome
 * @property string $cognome
 * @property string $dataNascita
 * @property string $email
 * @property string $passwordD
 * @property string $authKey
 */
class LogopedistaModel extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{logopedista}}';
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
            [['authKey'], 'string', 'max' => 50],
            [['email'], 'email'],
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
            'dataNascita' => 'Data di nascita',
            'email' => 'Email',
            'passwordD' => 'Password',
        ];
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
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey == $authKey;
    }

    public static function findByEmail($email){
        return self::findOne(['email' => $email]);
    }

    public function validatePassword($password){
        return $this->passwordD == md5($password);
    }
}

