<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class EsercizioModelSearch extends EsercizioModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'tipologia', 'logopedista', 'path', 'testo'], 'safe'],
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
    public function search($params){
        $query = EsercizioModel::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        \Yii::error($params);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['nome' => $this->nome]);
        $query->andFilterWhere(['like', 'logopedista', $this->logopedista])
            ->andFilterWhere(['like', 'tipologia', $this->tipologia]);

        return $dataProvider;
    }
}