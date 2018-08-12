<?php

namespace kosuhin\Yii2BaseKit\Forms;

use yii\base\Model;
use yii\data\BaseDataProvider;

class FilterForm extends Model
{
    public $filters = [];

    private $provider;

    private $advancedData = [];

    public function rules()
    {
        return [
            [['filters'], 'safe'],
        ];
    }

    public function getValue($key)
    {
        return isset($this->filters[$key]) ? $this->filters[$key] : null;
    }

    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return BaseDataProvider|null
     */
    public function getProvider()
    {
        return $this->provider;
    }

    public function isFiltered()
    {
        return count($this->filters) > 0;
    }

    /**
     * @return array
     */
    public function getAdvancedData(): array
    {
        return $this->advancedData;
    }

    /**
     * @param array $advancedData
     */
    public function setAdvancedData(array $advancedData)
    {
        $this->advancedData = $advancedData;
    }
}