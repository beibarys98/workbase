<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[FileType]].
 *
 * @see FileType
 */
class FileTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return FileType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return FileType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
