<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%work}}".
 *
 * @property int $id
 * @property string $title Work Title
 * @property string $author Work Author
 * @property int $year Year
 * @property int $course Course
 * @property int $program_id Education Program
 * @property int $subject_id Subject
 * @property int|null $file_type_id
 * @property int|null $is_visible
 * @property string $created_at Created
 * @property string|null $updated_at Updated
 *
 * @property Document[] $documents
 * @property FileType $fileType
 * @property Program $program
 * @property Subject $subject
 */
class Work extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%work}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_type_id'], 'required'],
            [['year'], 'match', 'pattern' => '/^\d{4}$/', 'message' => 'Year must be a four-digit number.'],
            [['course'], 'in', 'range' => [1, 2, 3, 4, 5], 'message' => 'Course must be between 1 and 5.'],

            [['title', 'author', 'year', 'course', 'program_id', 'subject_id'], 'required'],
            [['year', 'course', 'program_id', 'subject_id', 'file_type_id', 'is_visible'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 200],
            [['author'], 'string', 'max' => 100],
            [['file_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FileType::class, 'targetAttribute' => ['file_type_id' => 'id']],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::class, 'targetAttribute' => ['program_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'author' => Yii::t('app', 'Author'),
            'year' => Yii::t('app', 'Year'),
            'course' => Yii::t('app', 'Course'),
            'program_id' => Yii::t('app', 'Program ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'file_type_id' => Yii::t('app', 'File Type ID'),
            'is_visible' => Yii::t('app', 'Is Visible'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Documents]].
     *
     * @return \yii\db\ActiveQuery|DocumentQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::class, ['work_id' => 'id']);
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
     * Gets query for [[Program]].
     *
     * @return \yii\db\ActiveQuery|ProgramQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::class, ['id' => 'program_id']);
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery|SubjectQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::class, ['id' => 'subject_id']);
    }

    /**
     * {@inheritdoc}
     * @return WorkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WorkQuery(get_called_class());
    }
}
