<?php

namespace kosuhin\Yii2BaseKit\Services;

use kosuhin\Yii2BaseKit\Services\Helpers\ArrayHelperService;
use kosuhin\Yii2BaseKit\Services\Helpers\ObjectHelperService;
use kosuhin\Yii2BaseKit\Services\Helpers\PaginatorHelperService;
use Yii;

/**
 * @see SLBootstarper
 */
class BaseServiceLocator
{
    /** @var ObjectHelperService */
    public $objectHelperService = ObjectHelperService::class;

    /** @var ArrayHelperService */
    public $arrayHelperService = ArrayHelperService::class;

    /** @var PaginatorHelperService */
    public $paginatorHelperService = PaginatorHelperService::class;

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
     * If client wants some service return
     * it by calling component name
     *
     * @param $name
     * @return null|object
     * @throws \yii\base\InvalidConfigException
     */
    public function __get($name)
    {
        return Yii::$app->get($name);
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