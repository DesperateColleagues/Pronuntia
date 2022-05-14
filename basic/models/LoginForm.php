<?php

namespace app\models;

use Yii;
use yii\base\Model;


class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['email', 'email']
            // password is validated by validatePassword()
            //['password', 'validatePassword'],
        ];
    }

    public function login(){
        $sql = 'SELECT * FROM logopedista WHERE email = "'.$this->email.'" AND passwordD = "'.md5($this->password).'"';
        $command = Yii::$app->db->createCommand($sql);
        $reader = $command->query();

        //https://www.yiiframework.com/doc/api/2.0/yii-db-datareader

        if ($reader->getRowCount() == 1)
            return true;
        else
            return false;
    }

    private function estraiRighe($rs){
        // todo: metodo per estrarre righe dal result set e successivmante valorizzare l'oggetto logopedista
    }


}
