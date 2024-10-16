<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%file_type}}".
 *
 * @property int $id
 * @property string $title_kk Title(kk)
 * @property string $title_ru Title(ru)
 *
 * @property Document[] $documents
 */
class FileType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_kk', 'title_ru'], 'required'],
            [['title_kk', 'title_ru'], 'string', 'max' => 50],
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
     * Gets query for [[Documents]].
     *
     * @return \yii\db\ActiveQuery|DocumentQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::class, ['file_type_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return FileTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileTypeQuery(get_called_class());
    }
}
