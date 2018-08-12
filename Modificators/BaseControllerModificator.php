<?php

namespace kosuhin\Yii2BaseKit\Modificators;

use kosuhin\Yii2BaseKit\Controllers\BaseCRUDController;
use yii\base\Behavior;

/**
 * Базовый модификатор для BaseCRUDController
 * @see BaseCRUDController
 */
class BaseControllerModificator extends Behavior
{
    public function events()
    {
        return [
            BaseCRUDController::EVENT_AFTER_CHANGE_AND_MESSAGE_SENT => 'handleChangeAndMessageSent',
            BaseCRUDController::EVENT_AFTER_DELETE_ENTITY => 'handleAfterDelete',
            BaseCRUDController::EVENT_AFTER_BREADCRUMBS_SET => 'handleAfterBreadcrumbsSet',
            BaseCRUDController::EVENT_AFTER_UPDATE_MODEL_INIT => 'handleAfterUpdateModelInit',
            BaseCRUDController::EVENT_AFTER_CREATE_MODEL_INIT => 'handleAfterCreateModelInit',
            BaseCRUDController::EVENT_AFTER_INDEX_QUERY_CREATED => 'handleAfterIndexQueryCreated',
            BaseCRUDController::EVENT_AFTER_CHANGE_ENTITY => 'handleAfterChangeEntity',
        ];
    }

    /**
     * Возможность что-то сделать после изменения сущности
     * и выдачи флаш сообщения
     *
     * @param $event
     */
    public function handleChangeAndMessageSent($event)
    {
    }

    /**
     * Возможность что-то сделать после удаления сущности
     *
     * @param $event
     */
    public function handleAfterDelete($event)
    {
    }

    /**
     * Здесь возможно изменить хлебные крошки
     *
     * @param $event
     */
    public function handleAfterBreadcrumbsSet($event)
    {
    }

    /**
     * Вызывается после получения модели в контроллере
     * при создании модели
     * в $event->entity передается ссылка на модель
     * Есть возможность изменить модель перед выводом
     *
     * @param $event
     */
    public function handleAfterUpdateModelInit($event)
    {
    }

    /**
     * Вызывается после получения модели в контроллере
     * при обновлении модели
     * в $event->entity передается ссылка на модель
     * Есть возможность изменить модель перед выводом
     *
     * @param $event
     */
    public function handleAfterCreateModelInit($event)
    {
    }

    /**
     * Возможность что-то изменить после создания запроса
     * по получения списка моделей
     *
     * @param $event
     */
    public function handleAfterIndexQueryCreated($event)
    {
    }

    /**
     * Событие после изменения сущности
     *
     * @param $event
     */
    public function handleAfterChangeEntity($event)
    {
    }
}