<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%document}}".
 *
 * @property int $id
 * @property int $work_id Work Name
 * @property int $file_type_id File Type
 * @property string|null $file_name File Name
 * @property string $path
 * @property string $created_at
 *
 * @property FileType $fileType
 * @property Work $work
 */
class Document extends \yii\db\ActiveRecord
{
    public $doc;
    public $pdf;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%document}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['doc', 'file', 'extensions' => ['doc', 'docx']],
            ['pdf', 'file', 'extensions' => ['pdf']],

            [['work_id', 'file_type_id', 'path'], 'required'],
            [['work_id', 'file_type_id'], 'integer'],
            [['created_at'], 'safe'],
            [['file_name'], 'string', 'max' => 100],
            [['path'], 'string', 'max' => 255],
            [['file_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FileType::class, 'targetAttribute' => ['file_type_id' => 'id']],
            [['work_id'], 'exist', 'skipOnError' => true, 'targetClass' => Work::class, 'targetAttribute' => ['work_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'work_id' => Yii::t('app', 'Work ID'),
            'file_type_id' => Yii::t('app', 'File Type ID'),
            'file_name' => Yii::t('app', 'File Name'),
            'path' => Yii::t('app', 'Path'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[FileType]].
     *
     * @return \yii\db\ActiveQuery|FileTypeQuery
     */
    public function getFileType()
    {
        return $this->hasOne(FileType::class, ['id' => 'file_type_id']);
    }

    /**
     * Gets query for [[Work]].
     *
     * @return \yii\db\ActiveQuery|WorkQuery
     */
    public function getWork()
    {
        return $this->hasOne(Work::class, ['id' => 'work_id']);
    }

    /**
     * {@inheritdoc}
     * @return DocumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DocumentQuery(get_called_class());
    }
}
