<?php

namespace kosuhin\Yii2BaseKit\Models;

use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class SoftDeletableAR
 * @package app\ARs
 * @method softDelete
 */
abstract class SoftDeletableAR extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'isDeleted' => true
                ],
            ],
        ];
    }

    public static function find()
    {
        return parent::find()->andWhere(['isDeleted' => null]);
    }
}