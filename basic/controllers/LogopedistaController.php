<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class LogopedistaController extends Controller
{
    //private $account;

    /**
     * Esegue l'azione di registrazione al sistema tramite la FacadeAccount
     *
     * @param string $tipoAttore: indica il tipo di attore che si sta registrando al sistema
     *                              - log: logopedista -> DEFAULT
     *                              - car: caregiver
     *                              - ut: utente -> a livello di controller non serve fare differenza sul tipo di utente
    */
    public function actionRegistrazione($tipoAttore = 'log'){
        try {
            $post = Yii::$app->request->post(); // recupera l'array del post

            /** Se il model del form è UtenteModel il tipo attore è utente.
             * Al controller non interessa sapere lo specifico tipo di utente, in quanto la situazione di
             * presenza di un utente non autonomo è individuata da $tipoAttore = 'car'
             */
            if (array_key_exists('UtenteModel', $post))
                $tipoAttore = 'ut';

            // istanziazione della FacadeAccount e definizione del tipo di attore
            $account = new FacadeAccount();
            $account->setTipoAttore($tipoAttore);

            // esegue l'inserimento dati nel database sfruttando la FacadeAccount
            $res = $account->registrazione($post);

            if ($res) {

                if ($tipoAttore == 'log') {
                    return $this->render('@app/views/site/index');
                } else if ($tipoAttore == 'car') {
                    /* Il tipo di attore per la VIEW sarà utn. Al contrario del controller (dove registrare un utente
                        autonomo o non autonomo è eseguire la stessa operazione), nella VIEW è necessario specificare
                        il tipo di utente, in quanto utenti autonomi o non autonomi dispongono di campi diversi e
                        sono trattati in maniera diversa.*/
                    $tipoAttore = 'utn';

                    // recupera l'email del careviver dal post
                    $email = Yii::$app->request->post('CaregiverModel')['email'];
                    return $this->render('registrazione', [ 'caregiverEmail' => $email,
                        'attore' => $tipoAttore,]);
                } else if ($tipoAttore == 'ut') {
                    $this->layout = 'dashlog';
                    return $this->render('@app/views/logopedista/dashboardlogopedista');
                }
            } else {
                    Yii::error($res);
                }
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }

        // default view rendering
        return $this->render('registrazione', [
            'attore' => $tipoAttore, 'caregiverEmail' => null,
        ]);
    }

    public function actionDashboardlogopedista () {
        return $this->render('dashboardlogopedista');
    }

}