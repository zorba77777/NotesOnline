<?php

namespace app\models\query;

use app\models\Note;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Note]].
 *
 * @see \app\models\Note
 */
class NoteQuery extends ActiveQuery
{
    public function byCreator($userId)
    {
        return $this->andWhere(['creator_id' => $userId]);
    }

    public function onlyAccessed($userId){
        return $this->joinWith(Note::RELATION_ACCESS.' '.Note::RELATION_ACCESS)
            ->andWhere([Note::RELATION_ACCESS.'.user_id'=>$userId]);
    }


    /**
     * @inheritdoc
     * @return Note[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Note|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
