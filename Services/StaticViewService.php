<?php

namespace kosuhin\Yii2BaseKit\Services;

class StaticViewService
{
    /**
     * Рендерит JS файлы заменяя переменные $something$
     * на переменные из $params
     *
     * @param \yii\base\View $view
     * @param $fileName
     * @param $params
     * @return mixed
     */
    public function render(\yii\base\View $view, $fileName, $params)
    {
        $content = $view->render($fileName);

        return $this->renderLogic($content, $params);
    }

    public function renderFile($fileName, $params, $wrapper = '$')
    {
        $content = file_get_contents($fileName);

        return $this->renderLogic($content, $params, $wrapper);
    }

    private function renderLogic($content, $params, $wrapper = '$')
    {
        $paramKeys = $paramValues = [];
        foreach ($params as $key => $val) {
            $isObject = is_object($val);
            $isArray = is_array($val);
            if ($isObject) {
                foreach (get_object_vars($val) as $name => $parameter) {
                    $paramKey = $key . '.' . $name;
                    $paramKeys[] = $wrapper . $paramKey . $wrapper;
                    $paramValues[] = $parameter;
                }
            }
            if ($isArray) {
                foreach ($val as $inKey => $inVal) {
                    $paramKey = $key . '.' . $inKey;
                    $paramKeys[] = $wrapper . $paramKey . $wrapper;
                    $paramValues[] = $inVal;
                }
            }
            if (!$isObject && !$isArray) {
                $paramKeys[] = $wrapper . $key . $wrapper;
                $paramValues[] = $val;
            }
        }

        return str_replace($paramKeys, $paramValues, $content);
    }
}