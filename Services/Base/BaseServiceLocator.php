<?php

namespace kosuhin\Yii2BaseKit\Services\Base;

use kosuhin\Yii2BaseKit\Services\ArrayHelperService;
use kosuhin\Yii2BaseKit\Services\Base\ServiceLocator\ServiceLocatorGetMagic;
use kosuhin\Yii2BaseKit\Services\ConfigurationCheckService;
use kosuhin\Yii2BaseKit\Services\ObjectHelperService;
use kosuhin\Yii2BaseKit\Services\PaginatorHelperService;
use kosuhin\Yii2BaseKit\Services\StaticViewService;
use kosuhin\Yii2BaseKit\Services\TreeService;
use Yii;

/**
 * @see SLBootstarper
 */
class BaseServiceLocator extends ServiceLocatorGetMagic
{
    /** @var ObjectHelperService */
    public $objectHelperService = ObjectHelperService::class;

    /** @var ArrayHelperService */
    public $arrayHelperService = ArrayHelperService::class;

    /** @var PaginatorHelperService */
    public $paginatorHelperService = PaginatorHelperService::class;

    /** @var ConfigurationCheckService */
    public $configurationCheckService = ConfigurationCheckService::class;

    /** @var StaticViewService */
    public $staticViewService = StaticViewService::class;

    /** @var TreeService */
    public $treeService = TreeService::class;

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