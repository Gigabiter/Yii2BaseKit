<?php

namespace kosuhin\Yii2BaseKit\Services\Base;

use kosuhin\Yii2BaseKit\Components\BaseComponent;

/**
 * To use this class just make configuration
 * of your application like follow. Where
 * SL::class is className of class what
 * extended from BaseServiceLocator
 *
 * 'bootstrap' => [
 *      ...
 *          [
 *              'class' => SLBootstarper::class,
 *              'locator' => SL::class
 *          ],
 *      ...
 * ],
 * @see BaseServiceLocator
 */
class BootstrapServiceLocator extends BaseComponent
{
    public $locator;

    public $api = false;

    public function init()
    {
        parent::init();
        $locatorClass = $this->locator;
        $locatorClass::loadSLComponents();
        if ($this->getArrayHelperService()->getValue($this->api, 'enabled', true)) {
            $this->configureServicesApi();
        }
    }

    private function configureServicesApi()
    {
        $version = $this->getArrayHelperService()
            ->getValue($this->api, 'version', 1);
        $clientApiController = $this
            ->getArrayHelperService()
            ->getValue($this->api, 'controller', '/api/service');
        \Yii::$app->getUrlManager()->addRules(
            [
                '/api/v' . $version . '/<_format>/<service>' => $clientApiController,
                '/api/v' . $version . '/<_format>/<service>/<method>' => $clientApiController
            ]
        );
    }
}