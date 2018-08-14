<?php

namespace kosuhin\Yii2BaseKit\Widgets\OutFormSubmitButtons;

use kosuhin\Yii2BaseKit\Services\Base\BaseServiceLocator;
use yii\base\Widget;
use yii\web\View;

/**
 * Class OutFormSubmitButtons
 * @package kosuhin\Yii2BaseKit\Widgets\OutFormSubmitButtons
 */
class OutFormSubmitButtons extends Widget
{
    /**
     * 'buttons' => type, name, label, class
     * 'form_id' - Id of active form what need to be submit
     * 'action_field' - Field in what will be filled by submited button
     */
    public $options = [];

    public function init()
    {
        BaseServiceLocator::o()->configurationCheckService->checkOptions($this->options['buttons'], ['type', 'name', 'label', 'class']);
        BaseServiceLocator::o()->configurationCheckService->checkValue($this->options, 'form_id');
        BaseServiceLocator::o()->configurationCheckService->checkValue($this->options, 'action_field');
        OutFormSubmitButtonsAsset::register( $this->getView() );
        parent::init();
    }

    public function registerAssets(View &$view)
    {
        $formId = $this->options['form_id'];
        $actionField = $this->options['action_field'];
        $js = '';
        foreach ($this->options['buttons'] as $key => $button) {
            if ($button['type'] !== 'button') {
                continue;
            }
            $this->options['buttons'][$key]['id'] = 'outform-button-'.$key;
            $buttonOptionsJson = json_encode($this->options['buttons'][$key]);
            $js .= "new OutFormSubmitButton('{$formId}', '{$actionField}', {$buttonOptionsJson});";
        }
        $view->registerJs($js, $view::POS_LOAD);
    }

    public function run()
    {
        $view = $this->getView();
        $this->registerAssets($view);

        return $this->render('template', [
            'options' => $this->options,
        ]);
    }
}