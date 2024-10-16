<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%program}}".
 *
 * @property int $id
 * @property string $code Шифр
 * @property string $title_kk Название ОП(каз)
 * @property string $title_ru Название ОП(рус)
 */
class Program extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%program}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'title_kk', 'title_ru'], 'required'],
            [['code'], 'string', 'max' => 15],
            [['title_kk', 'title_ru'], 'string', 'max' => 150],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'title_kk' => Yii::t('app', 'Title Kk'),
            'title_ru' => Yii::t('app', 'Title Ru'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ProgramQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProgramQuery(get_called_class());
    }
}
