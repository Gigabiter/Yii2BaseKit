<?php

namespace kosuhin\Yii2BaseKit\Services\Base\ServiceLocator;

/**
 * Чтобы свойства сервис локатора не вызывались напрямую
 * нужно __get объявлять в отдельном классе
 *
 * @package kosuhin\Yii2BaseKit\Services\Base\ServiceLocator
 */
class ServiceLocatorGetMagic
{
    /**
     * If client wants some service return
     * it by calling component name
     *
     * @param $name
     * @return null|object
     * @throws \yii\base\InvalidConfigException
     */
    public function __get($name)
    {
        return \Yii::$app->get($name);
    }
}