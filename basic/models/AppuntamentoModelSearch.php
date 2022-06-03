<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AppuntamentoModel;

/**
 * AppuntamentoModelSearch represents the model behind the search form of `app\models\AppuntamentoModel`.
 */
class AppuntamentoModelSearch extends AppuntamentoModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dataAppuntamento', 'oraAppuntamento', 'logopedista', 'utente', 'caregiver'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $tipoAttore = TipoAttore::LOGOPEDISTA)
    {
        $query = AppuntamentoModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if ($tipoAttore == TipoAttore::LOGOPEDISTA) {
            $query->andFilterWhere([
                'dataAppuntamento' => $this->dataAppuntamento,
                'oraAppuntamento' => $this->oraAppuntamento,
            ]);

            $query->andFilterWhere(['like', 'logopedista', $this->logopedista])
                ->andFilterWhere(['like', 'utente', $this->utente])
                ->andFilterWhere(['like', 'caregiver', $this->caregiver]);
        } else if ($tipoAttore == TipoAttore::CAREGIVER){
                //$query->where("caregiver = '".$_COOKIE['caregiver']."'");
                $query->where('utente IS NULL');
        }

        return $dataProvider;
    }

    public function searchCaregiverAppuntamenti($email){
        $query = AppuntamentoModel::find()->where("caregiver = '".$email."'");
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
