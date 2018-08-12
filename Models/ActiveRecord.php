<?php

namespace kosuhin\Yii2BaseKit\Models;

use app\services\SL;
use yii\base\Event;

// TODO вероятнее всего нужно полностью прееделать это
class ActiveRecord extends \yii\db\ActiveRecord
{
    const EVENT_BEFORE_SAVE = 'eventBeforeSave';

    const EVENT_AFTER_SAVE = 'eventAfterSave';

    public $validateDelete = true;

    public static function search($filter)
    {
        $query = static::find();
        $registeredFilters = static::getFilters();
        if ($filter) {
            foreach ($filter as $key => $item) {
                if (!empty($registeredFilters[$key]) && $item != '') {
                    $callBack = $registeredFilters[$key];
                    $query = $callBack($query, $item);
                }
            }
        }

        return $query;
    }

    public function field($name)
    {
        return json_decode($this->$name, true);
    }

    public function saveJson($property, $array)
    {
        $this->$property = json_encode($array, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Проверка можно ли удалять эту модель
     * TODO Не уверен что это хорошее решение, нужно посмотреть есть ли стандартное
     *
     * @return bool
     */
    public function validateDelete()
    {
        return $this->validateDelete;
    }

    public function disableDelete()
    {
        $this->validateDelete = false;
    }

    public function beforeDelete()
    {
        return parent::beforeDelete() && $this->validateDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->trigger(static::EVENT_AFTER_SAVE, new Event(['data' => $this]));
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeSave($insert)
    {
        if ($this->hasAttribute('created_by') && $this->getIsNewRecord()) {
            $this->created_by = SL::o()->userService->getCurrentUserId();
        }

        if ($this->hasAttribute('created_on') && $this->getIsNewRecord()) {
            $this->created_on = date('Y-m-d H:i:s');
        }

        if ($this->hasAttribute('updated_on')) {
            $this->updated_on = date('Y-m-d H:i:s');
            SL::o()->entityChangeHistoryService->insert([
                'entity_class' => static::class,
                'entity_id' => $this->id,
                'changed_on' => $this->updated_on,
                'changed_by' => SL::o()->userService->getCurrentUserId(),
            ]);
        }

        if ($this->hasAttribute('updated_by')) {
            $this->updated_by = SL::o()->userService->getCurrentUserId();
        }

        $this->trigger(static::EVENT_BEFORE_SAVE, new Event(['data' => $this]));

        return parent::beforeSave($insert);
    }

    public static function name()
    {
        return 'Модель';
    }

    public function getMenuName()
    {
        return isset($this->name) ? $this->name : '';
    }

    protected $deleteRules = [

    ];

    protected static function filters()
    {
        return [];
    }

    protected static function getFilters()
    {
        return array_merge([
            'id' => function($query, $value) {
                return $query->andWhere(['id' => $value]);
            }
        ], static::filters());
    }
}
