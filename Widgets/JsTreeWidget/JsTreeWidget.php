<?php

namespace kosuhin\Yii2BaseKit\Widgets\JsTreeWidget;

use yii\base\Widget;

/**
 * Виджет jquery библиотеки jsTree
 * https://www.jstree.com/
 *
 * @package kosuhin\Yii2BaseKit\Widgets\JsTreeWidget
 */
class JsTreeWidget extends Widget
{
    /**
     * Адрес от которого будут браться
     * данные для построения дерева
     *
     * @var string
     */
    public $dataUrl;

    /**
     * Имя объекта с помощью которого можно управлять деревом динамически
     * Например можно вызывать TreeManager.build({}) - для построения дерева
     * или TreeManager.getSelectedNode() - Для получения выбранных узлов
     *
     * @var string
     */
    public $jsWidgetName = 'JsTreeWidget';

    /**
     * Первичные данные которые будут отправлены при
     * первом запросе к серверу за получением данных для дерева.
     * При последующих построениях дерева через TreeManager
     * передавайте эти данные в аргументе метода build
     * Пример: TreeManager.build(Ваши данные здесь)
     *
     * @var array
     */
    public $initialDataToServer = [];

    /**
     * Заголовки, использованные в виджете
     *
     * @var array
     */
    public $labels = [
        'loading' => 'Идет загрузка дерева...'
    ];

    /**
     * Дополнительные опции виджета
     *
     * @var array
     */
    public $options = [
        'multiple' => 'false',
    ];

    /**
     * Плагины jsTree
     *
     * @var array
     */
    public $plugins = [
        'search' => [
            'class' => 'form-control'
        ]
    ];

    public function init()
    {
        JsTreeWidgetAsset::register( $this->getView() );
        parent::init();
    }

    public function run()
    {
        return $this->render('template', [
            'dataUrl' => $this->dataUrl,
            'widgetName' => $this->jsWidgetName,
            'initialDataToServer' => $this->initialDataToServer,
            'labels' => $this->labels,
            'options' => $this->options,
            'plugins' => $this->plugins,
            'id' => $this->getId(),
        ]);
    }
}
