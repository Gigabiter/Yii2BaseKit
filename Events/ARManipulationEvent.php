<?php

namespace kosuhin\Yii2BaseKit\Events;

use kosuhin\Yii2BaseKit\Models\ActiveRecord;
use yii\base\Event;

class ARManipulationEvent extends Event
{
    /**
     * @var ActiveRecord
     */
    public $entity;
}