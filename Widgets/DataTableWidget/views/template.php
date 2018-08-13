<?php

use kosuhin\Yii2BaseKit\Services\Base\BaseServiceLocator;

$i18n = BaseServiceLocator::o()->staticViewService->render(
    $this,
    'i18n/ru_RU.json',
    []
);
$this->registerJs(
    BaseServiceLocator::o()->staticViewService->render(
        $this,
        'pluginInit.js',
        [
            'id' => '#data-table-' . $id,
            'i18n' => $i18n
        ]
    )
);
?>
<table id="data-table-<?= $id ?>">
    <thead>
    <tr>
        <?php foreach ($headers as $header) { ?>
            <th><?= $header ?></th>
        <?php } ?>
    </tr>
    </thead>
</table>
