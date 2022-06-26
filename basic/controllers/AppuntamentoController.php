<?php

namespace app\controllers;

use app\models\AppuntamentoModel;
use app\models\AppuntamentoModelSearch;
use app\models\DiagnosiModel;
use app\models\FacadeAppuntamento;
use app\models\TipoAttore;
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

    private $facadeAppuntamento;

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
    public function actionVisualizzaappuntamentiview($tipoAttore)
    {
        $searchModel = new AppuntamentoModelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $tipoAttore);

        if ($tipoAttore == TipoAttore::LOGOPEDISTA)
            $this->layout = 'dashlog';
        else if ($tipoAttore == TipoAttore::CAREGIVER)
            $this->layout = 'dashcar';
        // todo: caricare utente scemo

        return $this->render('visualizzaappuntamentiview', [
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
    public function actionDettagliappuntamento($dataAppuntamento, $oraAppuntamento, $logopedista)
    {
        $this->facadeAppuntamento = new FacadeAppuntamento();

        $post = Yii::$app->request->post();

        if (isset($post['confirm-button'])) { //conferma registrazione dell'appuntamento da parte del caregiver

            $dataAppuntamento = $this->facadeAppuntamento->getAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista)->dataAppuntamento;
            $oraAppuntamento = $this->facadeAppuntamento->getAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista)->oraAppuntamento;
            $logopedista = $this->facadeAppuntamento->getAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista)->logopedista;

            if ($this->facadeAppuntamento->modificaAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista, $post)) {
                return $this->redirect([
                    'dettagliappuntamento',
                    'dataAppuntamento' => $dataAppuntamento,
                    'oraAppuntamento' => $oraAppuntamento,
                    'logopedista' => $logopedista
                ]);
            }
        }

        return $this->render('dettagliappuntamento', [
            'model' => $this->facadeAppuntamento->getAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista)
        ]);
    }

    /**
     * Creates a new AppuntamentoModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreaappuntamento()
    {

        $this->facadeAppuntamento = new FacadeAppuntamento();

        $this->layout = 'dashlog';

        if ($this->request->isPost) {

            $post = $this->request->post();

            if ($this->facadeAppuntamento->aggiungiAppuntamento($post)) {
                return $this->redirect([
                    'dettagliappuntamento',
                    'dataAppuntamento' => $this->facadeAppuntamento->getDataAppuntamento($post),
                    'oraAppuntamento' => $this->facadeAppuntamento->getOraAppuntamento($post),
                    'logopedista' => $this->facadeAppuntamento->getLogopedista($post)
                ]);
            }
        } else {
            return $this->render('creaappuntamento', [
                'model' => $this->facadeAppuntamento->defaultModel(),
            ]);
        }
    }

    /**
     * Updates an existing AppuntamentoModel model.
     * If update is successful, the browser will be redirected to the 'dettagliappuntamento' page.
     * @param string $dataAppuntamento Data AppuntamentoModel
     * @param string $oraAppuntamento Ora AppuntamentoModel
     * @param string $logopedista Logopedista
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAggiornaappuntamento($dataAppuntamento, $oraAppuntamento, $logopedista)
    {
        $this->facadeAppuntamento = new FacadeAppuntamento();
        
        $post = $this->request->post();

        if (isset($_COOKIE['CurrentActor'])) {
            $tipoAttore = $_COOKIE['CurrentActor'];
        }

        if ($this->request->isPost) {

            try {

                $this->facadeAppuntamento->allegaDiagnosi($dataAppuntamento, $oraAppuntamento, $logopedista, $post);

            } catch (\Throwable $ex) {
                $this->facadeAppuntamento->modificaAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista, $post);
                // salvataggio del model appuntamento modificato
                // (in questo ramo, solo data e ora potrebbero essere modificate)
            }

            return $this->redirect([
                'dettagliappuntamento',
                'dataAppuntamento' => $this->facadeAppuntamento->getDataAppuntamento($post),
                'oraAppuntamento' => $this->facadeAppuntamento->getOraAppuntamento($post),
                'logopedista' => $this->facadeAppuntamento->getLogopedista($post)
            ]);
        }

        return $this->render('aggiornaappuntamento', [
            'model' => $this->facadeAppuntamento->getAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista),
            'diaModel' => new DiagnosiModel()
        ]);
    }

    /**
     * Deletes an existing AppuntamentoModel model.
     * If deletion is successful, the browser will be redirected to the 'visualizzaappuntamentiview' page.
     * @param string $dataAppuntamento Data AppuntamentoModel
     * @param string $oraAppuntamento Ora AppuntamentoModel
     * @param string $logopedista Logopedista
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionDelete($dataAppuntamento, $oraAppuntamento, $logopedista)
    {
        $this->facadeAppuntamento = new FacadeAppuntamento();

        $this->facadeAppuntamento->eliminaAppuntamento($dataAppuntamento, $oraAppuntamento, $logopedista);

        $tipoAttore = '';

        if (isset($_COOKIE['CurrentActor'])) {
            $tipoAttore = $_COOKIE['CurrentActor'];
            return $this->redirect(['visualizzaappuntamentiview?tipoAttore=' . $tipoAttore]);
        } else {
            // return fuori dall'app o ad un messaggio di errore? return $this->redirect(['visualizzaappuntamentiview?tipoAttore=' . $tipoAttore]);
        }
    }

    public function actionVisualizzadiagnosi()
    {
        if (isset($_COOKIE['CurrentActor'])) {
            $tipoAttore = $_COOKIE['CurrentActor'];
        }

        $query = 'SELECT appuntamento.dataAppuntamento, appuntamento.oraAppuntamento, appuntamento.utente, diagnosi.id, diagnosi.path FROM diagnosi JOIN appuntamento ON diagnosi.id = appuntamento.diagnosi';

        $sqlDiagnosisProvider = new SqlDataProvider(['sql' => $query]);

        return $this->render('visualizzadiagnosi', [
            'tipoAttore' => $tipoAttore,
            'dataProvider' => $sqlDiagnosisProvider
        ]);
    }

}
