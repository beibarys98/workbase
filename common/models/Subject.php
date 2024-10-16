<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%subject}}".
 *
 * @property int $id
 * @property string $title_kk Название(каз)
 * @property string $title_ru Название(рус)
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subject}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_kk', 'title_ru'], 'required'],
            [['title_kk', 'title_ru'], 'string', 'max' => 100],
            [['title_kk'], 'unique'],
            [['title_ru'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title_kk' => Yii::t('app', 'Title Kk'),
            'title_ru' => Yii::t('app', 'Title Ru'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SubjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubjectQuery(get_called_class());
    }
}
