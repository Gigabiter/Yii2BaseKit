<?php

namespace kosuhin\Yii2BaseKit\Services;

trait BaseServicesTrait
{
    /** @var ObjectHelperService */
    public static  $objectHelperService = ObjectHelperService::class;

    /** @var ArrayHelperService */
    public static  $arrayHelperService = ArrayHelperService::class;

    /** @var PaginatorHelperService */
    public static  $paginatorHelperService = PaginatorHelperService::class;

    /** @var ConfigurationCheckService */
    public static  $configurationCheckService = ConfigurationCheckService::class;

    /** @var StaticViewService */
    public static $staticViewService = StaticViewService::class;
}