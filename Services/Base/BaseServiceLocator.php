<?php

namespace kosuhin\Yii2BaseKit\Services\Base;

use kosuhin\Yii2BaseKit\Services\ArrayHelperService;
use kosuhin\Yii2BaseKit\Services\Base\ServiceLocator\ServiceLocatorGetMagic;
use kosuhin\Yii2BaseKit\Services\BaseServicesTrait;
use kosuhin\Yii2BaseKit\Services\ConfigurationCheckService;
use kosuhin\Yii2BaseKit\Services\ObjectHelperService;
use kosuhin\Yii2BaseKit\Services\PaginatorHelperService;
use kosuhin\Yii2BaseKit\Services\StaticViewService;
use Yii;

/**
 * @see SLBootstarper
 */
class BaseServiceLocator extends ServiceLocatorGetMagic
{
    use BaseServicesTrait;

    /** @var static */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @return static
     */
    public static function o()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Place all registerd compoentns to
     * Yii2 container
     *
     * @see SLBootstarper
     */
    public static function loadSLComponents()
    {
        $properties = get_class_vars(static::class);
        unset($properties['instance']);

        $compoentns = Yii::$app->getComponents();
        Yii::$app->setComponents(array_merge($compoentns, $properties));
    }
}