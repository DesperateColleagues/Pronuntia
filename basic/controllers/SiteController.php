<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\FacadeAccount;
use app\models\LoginForm;
use app\models\TipoAttore;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;


class SiteController extends Controller
{
    public $layout = 'main';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $post = Yii::$app->request->post();
        $account = new FacadeAccount();

        /** res assume due tipologie di valori:
         *  1) false: se il login non va a buon fine
         *  2) il valore del tipo di utente che sta effettuando l'accesso: se il login va a buon fine
         */
        $res = $account->accesso($post);

        Yii::error($res);

        if ($res == TipoAttore::LOGOPEDISTA) {
            $this->redirect('/logopedista/dashboardlogopedista?tipoAttore=' . $res);
        } else if ($res == TipoAttore::CAREGIVER) {
            $cookie_name = "caregiver";
            $cookie_value = Yii::$app->user->getId();
            VarDumper::dump($cookie_value);
            setcookie($cookie_name, $cookie_value, 0, "/");
            $this->redirect('/caregiver/dashboardcaregiver?tipoAttore=' . $res);
        } else if ($res == TipoAttore::UTENTE) {
            $cookie_name = "utente";
            $cookie_value = $post['LoginForm']['email'];
            setcookie($cookie_name, $cookie_value, 0, "/");
            $this->redirect('/utente/dashboardutente?tipoAttore=' . $res);
        } else if ($res ==  'n') {
            Yii::error('Errore nel login');
        }

        return $this->render('login', [
            'model' => $account->getLoginForm(),
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            if (isset($_COOKIE['CurrentActor']))
                setcookie('CurrentActor', time() - 3600);
            if (isset($_COOKIE['caregiver']))
                setcookie('caregiver', time() - 3600);
            Yii::$app->user->logout(true);
        }
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        /*$model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);*/
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionDownload()
    {
        clearstatcache();
        $file = Yii::$app->request->get('file');
        $path = Yii::$app->request->get('path');
        $root = Yii::getAlias('@webroot') . $path . $file;

        if (file_exists($root)) {
            return Yii::$app->response->sendFile($root);
        } else {
            throw new \yii\web\NotFoundHttpException("{$file} is not found!");
        }
    }
}
