<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class FacadeAppuntamento
{

    private $model;
    private $diaModel;

    public function getAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista)
    {
        if (($this->model = AppuntamentoModel::findOne(['dataAppuntamento' => $dataAppuntamento, 'oraAppuntamento' => $oraAppuntamento, 'logopedista' => $logopedista])) !== null) {
            return $this->model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function aggiungiAppuntamento($post)
    {
        $this->model = new AppuntamentoModel();

        if ($this->model->load($post) && $this->model->save()) {
            return true;
        }
    }

    public function eliminaAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista)
    {
        $this->model = new AppuntamentoModel();

        $this->model = $this->getAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista);
        $this->model->delete();
    }

    public function defaultModel()
    {

        $this->model = new AppuntamentoModel();
        $this->model->loadDefaultValues();

        return $this->model;
    }

    public function modificaAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista, $post)
    {
        $this->model = new AppuntamentoModel();

        $this->model = $this->getAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista);

        if ($this->model->load($post) && $this->model->save()) {
            return true;
        }
    }

    public function allegaDiagnosi($dataAppuntamento, $oraAppuntamento, $logopedista, $post)
    {
        $this->model = new AppuntamentoModel();
        $this->diaModel = new DiagnosiModel();

        $this->model = $this->getAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista);

        $rows = $this->ricercaVecchieDiagnosi($this->model);

        $this->model->load($post);
        $this->diaModel->load($post);

        $this->diaModel->mediaFile = UploadedFile::getInstance($this->diaModel, 'mediaFile');
        $this->diaModel->mediaFile->saveAs('diagnosi/Diagnosi.' . $this->diaModel->id . ".docx");

        $this->diaModel->path = 'diagnosi/Diagnosi.' . $this->diaModel->id . ".docx";

        $this->diaModel->save(); //salvataggio nuova diagnosi

        $this->model->diagnosi = $this->diaModel->id;
        // inserimento dell'id della diagnosi nel model dell'appuntamento
        // in maniera tale da valorizzare la chiave esterna

        $this->model->save();

        $this->eliminaVecchieDiagnosi($rows);
    }

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

    public function getDataAppuntamento($params)
    {
        $this->model = new AppuntamentoModel();
        $this->model->load($params);

        Yii::error($this->model->attributes);

        return $this->model->dataAppuntamento;
    }

    public function getOraAppuntamento($params)
    {
        $this->model = new AppuntamentoModel();
        $this->model->load($params);

        return $this->model->oraAppuntamento;
    }

    public function getLogopedista($params)
    {
        $this->model = new AppuntamentoModel();
        $this->model->load($params);

        return $this->model->logopedista;
    }
}
