<?php

namespace app\models;

use Yii;

class FacadeAppuntamento
{

    /*public function confermaAppuntamento($appParam){

        $model = new AppuntamentoModel();
        $model->load($appParam);

        $updatedModel = AppuntamentoModel::findByPK($model->dataAppuntamento,$model->logopedista,$model->oraAppuntamento);
        $updatedModel->caregiver = $model->caregiver;
        $updatedModel->utente = $model->utente;

        return $updatedModel->update();
    }*/

    public function ricercaVecchieDiagnosi($appModel)
    {

        return $rows = (new \yii\db\Query())
            ->select(['id', 'path'])
            ->from('appuntamento')
            ->join('INNER JOIN', 'diagnosi', 'diagnosi.id = appuntamento.diagnosi')
            ->where([
                'appuntamento.logopedista' => $appModel->logopedista, 'appuntamento.dataAppuntamento' => $appModel->dataAppuntamento,
                'appuntamento.utente' => $appModel->utente, 'appuntamento.oraAppuntamento' => $appModel->oraAppuntamento
            ])
            ->all();
    }

    public function eliminaVecchieDiagnosi($rows)
    {

        foreach ($rows as $row) {

            $path = $row['path'];
            $id = $row['id'];

            unlink($path);
            Yii::$app->db->createCommand()->delete('diagnosi', "id = '$id'")->execute();
        }
    }
}
