<?php

namespace kosuhin\Yii2BaseKit\Controllers;

use app\events\EventBeforeEntityChange;
use app\events\EventBeforeModelSave;
use app\helpers\AdminHelper;
use app\services\SL;
use kartik\grid\EditableColumnAction;
use kosuhin\Yii2BaseKit\Forms\FilterForm;
use kosuhin\Yii2BaseKit\Models\ActiveRecord;
use kosuhin\Yii2BaseKit\Models\SoftDeletableAR;
use kosuhin\Yii2BaseKit\Services\AbstractModelService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class BaseCRUDController extends Controller
{
    const EVENT_BEFORE_CHANGE_ENTITY = 'event.before.change.entity';

    const EVENT_AFTER_CHANGE_ENTITY = 'event.after.change.entity';

    const EVENT_AFTER_DELETE_ENTITY = 'event.after.delete.entity';

    const EVENT_AFTER_CHANGE_AND_MESSAGE_SENT = 'event.after.change.and.message.sent';

    const EVENT_AFTER_BREADCRUMBS_SET = 'event.after.breadcrumbs.set';

    const EVENT_AFTER_UPDATE_MODEL_INIT = 'event.after.update.model.init';

    const EVENT_AFTER_CREATE_MODEL_INIT = 'event.after.create.model.init';

    const EVENT_AFTER_INDEX_QUERY_CREATED = 'event.after.index.query.created';

    /** @var  ActiveRecord */
    protected $model;

    /** @var  AbstractModelService */
    protected $modelService;

    /** @var int Items per page */
    protected $ipp = 10;

    public $params = [
        'breadcrumbs' => []
    ];

    public $availableGroups = ['admin'];

    public $views = [
        'create' => 'create',
        'update' => 'create',
        'index' => 'index'
    ];

    public function actions()
    {
        // TODO переделать
        return ArrayHelper::merge(parent::actions(), [
            'liveedit' => [
                'class' => EditableColumnAction::class,
                'modelClass' => $this->model,
                'outputValue' => function ($model, $attribute, $key, $index) {
                    if ($attribute === 'user_id') {
                        return SL::o()->userService->getOne($model->user_id)->username;
                    }
                    if ($attribute === 'group_id') {
                        return SL::o()->groupService->getOne($model->group_id)->name;
                    }
                    if ($attribute === 'project_id') {
                        return SL::o()->projectService->getOne($model->project_id)->name;
                    }

                    return $model->$attribute;
                },
                'outputMessage' => function($model, $attribute, $key, $index) {
                    return '';
                },
            ]
        ]);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        // TODO выпилить такую проверку
                        'matchCallback' => function ($rule, $action) {
                            return SL::o()->permissionsService->curUserHasGroup($this->availableGroups);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * @param FilterForm $filterForm
     * @return FilterForm
     */
    protected function onFilter(FilterForm $filterForm)
    {
        return $filterForm;
    }

    public function actionIndex()
    {
        $modelClass = $this->model;
        $filters = array_merge(
            Yii::$app->request->get('FilterForm', []),
            Yii::$app->request->post('FilterForm', [])
        );
        $filterForm = new FilterForm($filters);
        $this->params['breadcrumbs'][] = ['label' => $modelClass::name()];
        $this->trigger(
            self::EVENT_AFTER_BREADCRUMBS_SET,
            (new EventBeforeEntityChange(['entity' => null]))
        );
        $ipp = Yii::$app->request->get('ipp', $this->ipp);
        $filterForm = $this->onFilter($filterForm);
        $query = $modelClass::search($filterForm->filters);
        $this->trigger(
            self::EVENT_AFTER_INDEX_QUERY_CREATED,
            (new EventBeforeEntityChange(['entity' => &$query]))
        );
        $order = ['id' => SORT_ASC];
        if ($query->orderBy) {
            $order = $query->orderBy;
        }
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $ipp,
            ],
            'sort' => [
                'defaultOrder' => $order,
            ],
        ]);
        $filterForm->setProvider($provider);

        return $this->render($this->views['index'], [
            'provider' => $provider,
            'modelClass' => $modelClass,
            'filterForm' => $filterForm,
        ]);
    }

    // TODO actionCreate и следующий actionUpdate дублируются нужно переделать в один метод
    public function actionCreate()
    {
        $modelClass = $this->model;
        /** @var ActiveRecord $model */
        $model = new $modelClass();
        $this->trigger(
            self::EVENT_AFTER_CREATE_MODEL_INIT,
            (new EventBeforeEntityChange(['entity' => &$model]))
        );
        $this->params['breadcrumbs'][] = ['label' => $modelClass::name(), 'url' => Url::toRoute(['index'])];
        $this->params['breadcrumbs'][] = ['label' => $model->getMenuName()];
        $this->trigger(
            self::EVENT_AFTER_BREADCRUMBS_SET,
            (new EventBeforeEntityChange(['entity' => $model]))
        );
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // TODO пересмотреть это событие, возможно лучше передвавать обработку только в сервис
            $this->trigger(
                self::EVENT_BEFORE_CHANGE_ENTITY,
                (new EventBeforeEntityChange(['entity' => $model]))
            );
            $this->triggerModelServiceBeforeSave($model);
            $model->save();
            $this->trigger(
                self::EVENT_AFTER_CHANGE_ENTITY,
                (new EventBeforeEntityChange(['entity' => $model]))
            );
            $modelName = ' ';
            if ($model instanceof \kosuhin\Yii2BaseKit\Models\ActiveRecord) {
                $modelName .= $model::name();
            }
            Yii::$app->getSession()->setFlash('success', 'Успешно создано'.$modelName);
            $this->trigger(
                self::EVENT_AFTER_CHANGE_AND_MESSAGE_SENT,
                (new EventBeforeEntityChange(['entity' => $model]))
            );
            return $this->redirect('index');
        }

        return $this->render($this->views['create'], [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $modelClass = $this->model;
        $model = $modelClass::findOne($id);
        if (!$model) {
            throw new \LogicException('Entity not found!');
        }
        $this->trigger(
            self::EVENT_AFTER_UPDATE_MODEL_INIT,
            (new EventBeforeEntityChange(['entity' => &$model]))
        );
        $this->params['breadcrumbs'][] = ['label' => $modelClass::name(), 'url' => Url::toRoute(['index'])];
        $this->params['breadcrumbs'][] = ['label' => $model->getMenuName()];
        $this->trigger(
            self::EVENT_AFTER_BREADCRUMBS_SET,
            (new EventBeforeEntityChange(['entity' => $model]))
        );
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // TODO пересмотреть это событие, возможно лучше передвавать обработку только в сервис
            $this->trigger(
                self::EVENT_BEFORE_CHANGE_ENTITY,
                (new EventBeforeEntityChange(['entity' => $model]))
            );
            $this->triggerModelServiceBeforeSave($model);
            $model->save();
            $this->trigger(
                self::EVENT_AFTER_CHANGE_ENTITY,
                (new EventBeforeEntityChange(['entity' => $model]))
            );
            $modelName = ' ';
            if ($model instanceof \kosuhin\Yii2BaseKit\Models\ActiveRecord) {
                $modelName .= $model::name();
            }
            Yii::$app->getSession()->setFlash('success', 'Успешно обновлено'.$modelName);
            if (isset($_POST['apply']) && $_POST['apply'] === '1') {
                $this->trigger(
                    self::EVENT_AFTER_CHANGE_AND_MESSAGE_SENT,
                    (new EventBeforeEntityChange(['entity' => $model]))
                );
                return $this->refresh();
            }
            $this->trigger(
                self::EVENT_AFTER_CHANGE_AND_MESSAGE_SENT,
                (new EventBeforeEntityChange(['entity' => $model]))
            );

            return $this->refresh();
        }

        return $this->render($this->views['update'], [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $modelClass = $this->model;
        /** @var ActiveRecord $model */
        $model = $modelClass::findOne($id);

        if ($model instanceof SoftDeletableAR) {
            if ($model->isDeleted) {
                Yii::$app->getSession()->setFlash('success', 'Успешно удалено');
            } else {
                if ($model->softDelete()) {
                    Yii::$app->getSession()->setFlash('success', 'Успешно удалено');
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Ошибка удаления');
                }
            }
        } else {
            if ($model->delete()) {
                Yii::$app->getSession()->setFlash('success', 'Успешно удалено');
            } else {
                Yii::$app->getSession()->setFlash('error', 'Ошибка удаления');
            }
        }
        $this->trigger(
            self::EVENT_AFTER_DELETE_ENTITY,
            (new EventBeforeEntityChange(['entity' => $model]))
        );

        return $this->redirect('index');
    }

    /**
     * Массовое удаление
     */
    public function actionBatchRemove()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->model;
        $ids = Yii::$app->request->post('ids');
        $models = $modelClass::findAll(['id' => $ids]);
        $deletedCount = 0;
        foreach ($models as $model) {
            if ($model instanceof SoftDeletableAR) {
                $model->softDelete();
                $deletedCount++;
            }
        }

        return ['result' => $deletedCount];
    }

    /**
     * Массовая активация
     */
    public function actionBatchActive()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->model;
        $ids = Yii::$app->request->post('ids');
        $models = $modelClass::findAll(['id' => $ids]);
        foreach ($models as $model) {
            $model->is_active = !$model->is_active;
            $model->save();
        }

        return count($models);
    }

    /**
     * @param $id
     * @return ActiveRecord
     */
    private function getModel($id)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->model;
        $model = $modelClass::findOne($id);

        return $model;
    }

    /**
     * Информируем сервис модели о том, что
     * модель скоро сохранится
     *
     * @param $model
     * @throws \yii\base\InvalidConfigException
     */
    protected function triggerModelServiceBeforeSave($model)
    {
        if (!$this->modelService) {
            return;
        }

        $serviceName = AdminHelper::serviceClassToServiceName($this->modelService);
        Yii::$app->get($serviceName)->trigger(
            AbstractModelService::EVENT_BEFORE_MODEL_SAVE,
            (new EventBeforeModelSave(['entity' => $model]))
        );
    }
}