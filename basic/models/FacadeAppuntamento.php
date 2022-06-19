<?php

namespace app\models;

class FacadeAppuntamento
{
    public function aggiuntaAppuntamento($appParam){

        $model = new AppuntamentoModel();
        $model->load($appParam);

        return $model->insert();
    }

    public function confermaAppuntamento($appParam){

        $model = new AppuntamentoModel();
        $model->load($appParam);

        $updatedModel = AppuntamentoModel::findByPK($model->dataAppuntamento,$model->logopedista,$model->oraAppuntamento);
        $updatedModel->caregiver = $model->caregiver;
        $updatedModel->utente = $model->utente;

        return $updatedModel->update();
    }
}