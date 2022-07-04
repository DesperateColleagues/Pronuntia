<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "diagnosi".
 *
 * @property string $id
 * @property resource|null $mediaFile
 * @property string|null $path
 *
 * @property AppuntamentoModel[] $appuntamentoModels
 */
class DiagnosiModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diagnosi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['mediaFile'], 'file'],
            [['id'], 'string', 'max' => 23],
            [['path'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mediaFile' => 'Media File',
            'path' => 'Path',
        ];
    }

    /**
     * Gets query for [[AppuntamentoModels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppuntamentoModels()
    {
        return $this->hasMany(AppuntamentoModel::className(), ['diagnosi' => 'id']);
    }
}
