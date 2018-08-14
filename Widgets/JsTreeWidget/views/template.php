<?php

/** @var $id */

use kosuhin\Yii2BaseKit\Services\ArrayHelperService;
use kosuhin\Yii2BaseKit\Services\StaticViewService;

/** @var $dataUrl */
/** @var $widgetName */
/** @var $initialDataToServer */
/** @var $labels */
/** @var $options */
/** @var $plugins */

$id = "jstree-$id";
$searchId = "jstree-search-$id";
$pluginsKeys = array_keys($plugins);
/** @var ArrayHelperService $arrayService */
$arrayService = \Yii::$app->get('arrayHelperService');
/** @var StaticViewService $staticViewService */
$staticViewService = \Yii::$app->get('staticViewService');
$this->registerJs(
    $staticViewService->renderFile(__DIR__.'/pluginInit.js', [
        'id' => "#$id",
        'searchId' => "#$searchId",
        'dataUrl' => $dataUrl,
        'options' => $options,
        'plugins' => $arrayService->toJson($pluginsKeys),
        'labels' => $labels,
        'initialDataToServer' => $arrayService->toJson($initialDataToServer),
        'widgetName' => $widgetName,
    ], '__')
);
?>
<?php if (in_array('search', $pluginsKeys)) { ?>
<input id="jstree-search-<?= $id ?>" placeholder="<?= $arrayService->getValue($labels, 'search', 'Найти') ?>" type="text" class="<?= $arrayService->getValue($plugins['search'], 'class', '') ?>">
<?php } ?>
<div id="<?= $id ?>" data-url="<?= $dataUrl ?>"></div>