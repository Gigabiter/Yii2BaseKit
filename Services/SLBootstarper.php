<?php

namespace kosuhin\Yii2BaseKit\Services;

use yii\base\Component;

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
class SLBootstarper extends Component
{
    public $locator;

    public function init()
    {
        parent::init();
        $locatorClass = $this->locator;
        $locatorClass::loadSLComponents();
    }
}