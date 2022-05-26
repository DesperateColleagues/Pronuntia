<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\FacadeAccount;
use app\models\LoginForm;
use app\models\TipoAttore;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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

        if($res == TipoAttore::LOGOPEDISTA) {
                $this->layout = 'dashlog';
                return $this->render('@app/views/logopedista/dashboardlogopedista');
        } else if ($res == TipoAttore::CAREGIVER) {
                $this->layout = 'dashcar';
                return $this->render('@app/views/caregiver/dashboardcaregiver');
        } else if ($res == TipoAttore::UTENTE) {
                $this->layout = 'dashutn';
                return $this->render('@app/views/utente/dashboardutente');
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
        if(!Yii::$app->user->isGuest)
            Yii::$app->user->logout(true);
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
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
}
