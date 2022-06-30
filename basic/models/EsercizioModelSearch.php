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
        return  new ActiveDataProvider([
            'query' => EsercizioModel::find(),
        ]);
    }
}