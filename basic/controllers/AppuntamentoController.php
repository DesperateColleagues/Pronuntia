<?php

namespace app\controllers;

use app\models\FacadeAppuntamento;
use Exception;
use yii\web\Controller;
use Yii;

class AppuntamentoController extends Controller
{
    public function actionInserisciappuntamento()
    {
        try {
            $post = Yii::$app->request->post();
            $appuntamento = new FacadeAppuntamento();

            $res = $appuntamento->aggiuntaAppuntamento($post);

        } catch(Exception $e) {
            Yii::error($e);
        }

        return $this->render('inserisciappuntamento');
    }
}