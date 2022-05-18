<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logopedista".
 *
 * @property string $nome
 * @property string $cognome
 * @property string $dataNascita
 * @property string $email
 * @property string $passwordD
 */
class LogopedistaModel extends \yii\db\ActiveRecord
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
}

