<?php

namespace app\controllers;

use app\models\AppuntamentoModel;
use app\models\AppuntamentoModelSearch;
use app\models\CaregiverModel;
use app\models\DiagnosiModel;
use app\models\TipoAttore;
use Exception;
use yii\base\Model;
use yii\helpers\Console;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\data\SqlDataProvider;
use yii\web\UploadedFile;
use yii\helpers\VarDumper;

/**
 * AppuntamentoController implements the CRUD actions for AppuntamentoModel model.
 */

class AppuntamentoController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all AppuntamentoModel models.
     *
     * @return string
     */
    public function actionIndex($tipoAttore)
    {
        $searchModel = new AppuntamentoModelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $tipoAttore);

        if ($tipoAttore == TipoAttore::LOGOPEDISTA)
            $this->layout = 'dashlog';
        else if ($tipoAttore == TipoAttore::CAREGIVER)
            $this->layout = 'dashcar';
        // todo: caricare utente scemo

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AppuntamentoModel model.
     * @param string $dataAppuntamento Data AppuntamentoModel
     * @param string $oraAppuntamento Ora AppuntamentoModel
     * @param string $logopedista Logopedista
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($dataAppuntamento, $oraAppuntamento, $logopedista)
    {
        $post = Yii::$app->request->post();

        if (isset($post['confirm-button'])) {
            $model = $this->findModel($dataAppuntamento, $oraAppuntamento, $logopedista);

            if ($model->load($post) && $model->save()) {
                return $this->redirect(['view', 'dataAppuntamento' => $model->dataAppuntamento, 'oraAppuntamento' => $model->oraAppuntamento, 'logopedista' => $model->logopedista]);
            }
        }

        return $this->render('view', [
            'model' => $this->findModel($dataAppuntamento, $oraAppuntamento, $logopedista),
        ]);
    }

    /**
     * Creates a new AppuntamentoModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AppuntamentoModel();
        $this->layout = 'dashlog';
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'dataAppuntamento' => $model->dataAppuntamento, 'oraAppuntamento' => $model->oraAppuntamento, 'logopedista' => $model->logopedista]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AppuntamentoModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $dataAppuntamento Data AppuntamentoModel
     * @param string $oraAppuntamento Ora AppuntamentoModel
     * @param string $logopedista Logopedista
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($dataAppuntamento, $oraAppuntamento, $logopedista)
    {
        $model = $this->findModel($dataAppuntamento, $oraAppuntamento, $logopedista);

        $diaModel = new DiagnosiModel();

        if (isset($_COOKIE['CurrentActor'])) {
            $tipoAttore = $_COOKIE['CurrentActor'];
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $diaModel->load($this->request->post())) {

            VarDumper::dump($model->diagnosi);

            try {
                $diaModel->mediaFile = UploadedFile::getInstance($diaModel, 'mediaFile');
                $diaModel->mediaFile->saveAs('diagnosi/Diagnosi.' . $diaModel->id . ".docx");

                $diaModel->path = 'diagnosi/Diagnosi.' . $diaModel->id . ".docx";

                $diaModel->save();

                $model->diagnosi = $diaModel->id;
            } catch (\Throwable $ex) {
                $model->save();
            }

            $model->save();

            return $this->redirect(['view', 'dataAppuntamento' => $model->dataAppuntamento, 'oraAppuntamento' => $model->oraAppuntamento, 'logopedista' => $model->logopedista]);
        }

        return $this->render('update', [
            'model' => $model,
            'diaModel' => $diaModel
        ]);
    }

    /**
     * Deletes an existing AppuntamentoModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $dataAppuntamento Data AppuntamentoModel
     * @param string $oraAppuntamento Ora AppuntamentoModel
     * @param string $logopedista Logopedista
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionDelete($dataAppuntamento, $oraAppuntamento, $logopedista)
    {
        $this->findModel($dataAppuntamento, $oraAppuntamento, $logopedista)->delete();

        $tipoAttore = '';

        if (isset($_COOKIE['CurrentActor'])) {
            $tipoAttore = $_COOKIE['CurrentActor'];
        }

        Yii::error($tipoAttore);

        return $this->redirect(['index?tipoAttore=' . $tipoAttore]);
    }

    public function actionShow()
    {
        if (isset($_COOKIE['CurrentActor'])) {
            $tipoAttore = $_COOKIE['CurrentActor'];
        }

        $query = 'SELECT appuntamento.dataAppuntamento, appuntamento.utente, diagnosi.id, diagnosi.path FROM diagnosi JOIN appuntamento ON diagnosi.id = appuntamento.diagnosi';

        $sqlDiagnosisProvider = new SqlDataProvider(['sql' => $query]);

        VarDumper::dump($tipoAttore);

        return $this->render('show', [
            'tipoAttore' => $tipoAttore,
            'dataProvider' => $sqlDiagnosisProvider
        ]);
    }


    /**
     * Finds the AppuntamentoModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $dataAppuntamento Data AppuntamentoModel
     * @param string $oraAppuntamento Ora AppuntamentoModel
     * @param string $logopedista Logopedista
     * @return AppuntamentoModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($dataAppuntamento, $oraAppuntamento, $logopedista)
    {
        if (($model = AppuntamentoModel::findOne(['dataAppuntamento' => $dataAppuntamento, 'oraAppuntamento' => $oraAppuntamento, 'logopedista' => $logopedista])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
