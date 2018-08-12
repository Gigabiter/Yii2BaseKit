<?php

namespace kosuhin\Yii2BaseKit\Services;

use kosuhin\Yii2BaseKit\Behaviours\BaseModelModificator;
use kosuhin\Yii2BaseKit\Events\ARManipulationEvent;
use kosuhin\Yii2BaseKit\Helpers\ValueStub;
use yii\base\Component;
use yii\base\Event;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class BaseModelService extends Component
{
    const EVENT_BEFORE_MODEL_SAVE = 'event.before.model.save';

    /**
     * @var ActiveRecord
     */
    protected $modelClass;

    /**
     * Поле модели, которое может быть
     * использовано для вывода моделей в
     * выпадающих списках
     *
     * @var string
     */
    protected $modelNameField = 'name';

    public function init()
    {
        $this->on(self::EVENT_BEFORE_MODEL_SAVE, [$this, 'onBeforeModelSave']);
        parent::init();
    }

    /**
     * API метод, который отвечает за проверку того
     * возможно ли удалить сущность
     */
    public function apiCheckDelete()
    {
        $id = \Yii::$app->request->post('id');
        $model = $this->getOne($id);
        $errors = [];
        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior instanceof BaseModelModificator) {
                $behavior->handleBeforeDelete((new Event(['data' => ['type' => 'api']])));
                $errors = array_merge($errors, $behavior->getModificatorErrors());
            }
        }

        return ['errors' => $errors];
    }

    public function apiHandleEntity()
    {
        $method = \Yii::$app->request->getMethod();
        $result = [];
        switch ($method) {
            case 'GET':
                if ($id = \Yii::$app->request->get('id')) {
                    $result = $this->apiGet($id);
                } else {
                    $page = \Yii::$app->request->get('page', 1);
                    $pageCount = \Yii::$app->request->get('pageCount', 10);
                    $order = \Yii::$app->request->get('order', 'id');
                    $result = $this->apiList($page, $pageCount, $order);
                }
                break;
            case 'POST':
                $result = $this->apiCreate();
                break;
            case 'PUT':
                $id = \Yii::$app->request->get('id', null);
                $result = $this->apiUpdate($id);
                break;
            case 'DELETE':
                $id = \Yii::$app->request->get('id', null);
                $result = $this->apiDelete($id);
                break;
        }

        return $result;
    }

    /**
     * API метод получения одной сущности
     */
    public function apiGet($id)
    {
        $model = $this->getOne($id);

        return [
            'status' => (bool)$model->hasAttribute('id'),
            'entity' => $model,
        ];
    }

    /**
     * API метод получения списка сущностей
     */
    public function apiList($page, $pageCount, $order)
    {
        $total = $this->countByCondition();
        $fromLimit = \Yii::$app->get('paginatorHelperService')->getFromLimit($page - 1, $pageCount, $total);
        $orderDirection = 'ASC';
        if (substr($order, 0, 1) === '-') {
            $orderDirection = 'DESC';
        }
        $order = str_replace('-', '', $order) . ' ' . $orderDirection;
        $models = $this->getAllCondition([], false, $order, $fromLimit);

        return [
            'status' => (bool)count($models),
            'total' => (int)$total,
            'pageCount' => (int)$pageCount,
            'entities' => $models,
        ];
    }

    /**
     * API метод создания новой сущности
     */
    public function apiCreate()
    {
        $modelClass = $this->modelClass;
        /** @var ActiveRecord $object */
        $object = new $modelClass;
        $shortName = \Yii::$app->get('objectHelperService')->getShortClass($object);
        $data = [
            $shortName => \Yii::$app->request->post(),
        ];
        $status = $errors = false;
        if ($object->load($data) && $object->validate()) {
            $status = true;
            $object->save();
        } else {
            $errors = $object->getErrors();
        }

        return [
            'status' => $status,
            'errors' => $errors,
            'entity' => $object,
        ];
    }

    /**
     * API метод изменения сущности
     */
    public function apiUpdate($id)
    {
        if (!\Yii::$app->request->isPut) {
            return [
                'status' => false,
            ];
        }
        /** @var ActiveRecord $object */
        $object = $this->findOne($id);
        $status = $errors = false;
        $data = $this->plainDataToARData(
            json_decode(\Yii::$app->request->getRawBody()),
            $object
        );
        if ($object) {
            if ($object->load($data) && $object->validate()) {
                $status = true;
            } else {
                $errors = $object->getErrors();
            }
        }

        return [
            'status' => $status,
            'errors' => $errors,
            'entity' => $object,
        ];
    }

    /**
     * API метод удаления сущности
     */
    public function apiDelete($id)
    {
        if (!\Yii::$app->request->isDelete) {
            return [
                'status' => false,
            ];
        }
        /** @var ActiveRecord $object */
        $object = $this->findOne($id);
        $status = $errors = false;
        if ($object->delete()) {
            $status = true;
        } else {
            $object->getErrors();
        }

        return [
            'status' => $status,
            'errors' => $errors,
        ];
    }

    /**
     * @deprecated Это была первая версия управления сущностями при их изменениях, лучше для этих целей
     * использовать ARModificators
     * @see ARModificators/
     */
    public function onBeforeModelSave(ARManipulationEvent $event)
    {
        // Реализуется потомками =)
    }

    public function plainDataToARData($data, $object)
    {
        $shortName = \Yii::$app->get('objectHelperService')->getShortClass($object);

        return [
            $shortName => $data,
        ];
    }

    /**
     * Обертка для удаления много записей
     *
     * @param array $condition
     * @return int
     */
    public function deleteAll($condition = [])
    {
        /** @var ActiveRecord $model */
        $model = $this->modelClass;
        return $model::deleteAll($condition);
    }

    /**
     * Вставляет много строк в Модель
     *
     * @param string $tableName
     * @param array $attributes
     * @param array $rows
     */
    public function insertAll($tableName, $attributes = [], $rows = [])
    {
        \Yii::$app->db->createCommand()->batchInsert($tableName, $attributes, $rows)->execute();
    }

    /**
     * Возвращает массив сущностей, готовых
     * для отображения в выпадающем списке
     *
     * @param array $exclude
     * @return array
     */
    public function findListItems($exclude = [])
    {
        /** @var ActiveRecord $model */
        $model = $this->modelClass;
        $query = $model::find();
        if ($exclude && $exclude[0] !== null) {
            $query->andWhere(['not in', 'id', $exclude]);
        }
        $groups = $query->all();
        $result = [0 => '-'];
        foreach ($groups as $group) {
            $result[$group['id']] = $group[$this->modelNameField];
        }

        return $result;
    }

    /**
     * @deprecated Плохое имя
     */
    public function getToList($exclude = [])
    {
        return $this->findListItems($exclude);
    }

    /**
     * Создать новый экземпляр модели
     *
     * @param array $data
     * @return mixed
     */
    public function newInstance($data = [])
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass;
        foreach ($data as $property => $datum) {
            $model->$property = $datum;
        }

        return $model;
    }

    /**
     * Найти модель по условию или
     * создать новую пустую модель
     *
     * @param array $condition
     * @return array|null|ActiveRecord
     */
    public function findOrCreateNew($condition = [])
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        if ($model = $modelClass::find()->where($condition)->one()) {
            return $model;
        }

        return new $modelClass();
    }

    // TODO похоже на дубль предыдущего метода, нужно разобраться
    public function createIfNotExists($condition = [])
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        if ($model = $modelClass::find()->where($condition)->one()) {
            return false;
        }

        return new $modelClass();
    }

    /**
     * Получить одну сущность по ID
     *
     * @param $id
     * @return ValueStub|null|static
     */
    public function findOne($id)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $realModel = $modelClass::findOne($id);
        if (!$realModel) {
            return new ValueStub();
        }

        return $realModel;
    }

    /**
     * @deprecated Плохое имя
     */
    public function getOne($id)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $realModel = $modelClass::findOne($id);
        if (!$realModel) {
            // TODO проверить что заглушка не ломает ничего
            return new ValueStub();
        }

        return $realModel;
    }

    /**
     * Получить один по условию
     *
     * @param array $condition
     * @return array|null|ActiveRecord
     */
    public function getOneByCondition($condition = [], $orderBy = null)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $query = $modelClass::find()->where($condition);
        if ($orderBy) {
            $query->orderBy($orderBy);
        }
        $realModel = $query->one();

        return $realModel;
    }

    /**
     * Получить много сущностей
     *
     * @param bool $asArray
     * @return array|ActiveRecord[]
     */
    public function getAll($asArray = false)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;

        if ($asArray) {
            return $modelClass::find()->asArray()->all();
        }

        return $modelClass::find()->all();
    }

    public function getAllCondition($condition = [], $asArray = false, $orderBy = false, $limit = false)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $query = $modelClass::find();
        if ($condition) {
            $query->where($condition);
        }
        if ($limit) {
            $query->limit($limit['limit'])->offset($limit['offset']);
        }
        if ($orderBy) {
            $query->orderBy($orderBy);
        }
        if ($asArray) {
            return $query->asArray()->all();
        }

        return $query->all();
    }

    /**
     * Получить много сущностей по условию
     *
     * @param array $condition
     * @return static[]
     */
    public function getMany($condition = [])
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;

        return $modelClass::findAll($condition);
    }

    /**
     * Получить дерево родителей
     *
     * @param $startFromId
     * @return array
     */
    public function getTreeOfParents($startFromId)
    {
        $result = [];
        $items = $this->getMany(['parent_id' => $startFromId]);
        if ($items) {
            foreach ($items as $item) {
                $result[$startFromId]['children'] = $this->getTreeOfParents($item->id);
                $result[$startFromId]['item'] = $item;
            }
        }

        return $result;
    }

    /**
     * Получить аттрибут сущности,
     * если атрибута нет вернуть '-'
     * Полезно для вывода в таблицах
     *
     * @param $name
     * @param $id
     * @return mixed|string
     */
    public function findAttr($name, $id)
    {
        /** @var ActiveRecord $model */
        $model = $this->modelClass;
        $group = $model::findOne($id);

        return $group ? $group->$name : '-';
    }

    /**
     * Посчиттаь кол-во по условию
     *
     * @param $condition
     * @return mixed
     */
    public function countByCondition($condition = [])
    {
        $model = $this->modelClass;

        return $model::find()->andWhere($condition)->count();
    }

    /**
     * Построить Active провайдер по переданным
     * условиям, может быть полезно для таблиц
     *
     * $this->buildDataProviderWithConditions([
     *      [
     *          'type' => 'andWhere',
     *          'value' => ['id' => 10]
     *      ]
     * ])
     */
    public function buildDataProviderWithConditions($conditions = [])
    {
        $model = $this->modelClass;
        $query = $model::find();
        foreach ($conditions as $condition) {
            $type = $condition['type'];
            $query->$type($condition['value']);
        }
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $provider;
    }

    /**
     * @param array $data
     * @return ActiveRecord
     */
    public function insert($data = [])
    {
        $model = $this->modelClass;
        $object = new $model($data);
        $result = $object->save();
        $errors = $object->getErrors();

        return $object;
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
