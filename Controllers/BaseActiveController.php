<?php

namespace kosuhin\Yii2BaseKit\Controllers;

/**
 * АПИ обращается к сервисам
 * Пример запроса:
 *      /api/v1/[json|xml]/fieldService/FieldLabel - Такой запрос вызовет
 *      сервис FieldService и обратиться к его методу apiFieldLabel
 *      ответ будет сформирован в JSON
 *
 * @package app\controllers\api\v1
 */
class BaseActiveController extends BaseCRUDController
{
    public $availableGroups = ['admin', 'user'];

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::className(),
                'only' => ['service'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                    'application/xml' => \yii\web\Response::FORMAT_XML,
                ],
            ],
        ];
    }

    /**
     * TODO Добавить больше проверок для сервиса
     *
     * @param $service
     * @param null $method
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionService($service, $method = null)
    {
        $service = \Yii::$app->get($service);
        if (!$service) {
            throw new \LogicException('Сервис не существует');
        }
        if ($method) {
            $method = 'api' . $method;
            if (!method_exists($service, $method)) {
                throw new \LogicException('Метод не существует');
            }
        }
        $method = 'apiHandleEntity';

        return $service->$method();
    }
}