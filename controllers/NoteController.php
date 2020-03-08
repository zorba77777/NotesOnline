<?php

namespace app\controllers;

use Yii;
use app\models\Note;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\search\NoteSearch;
use yii\filters\AccessControl;

/**
 * NoteController implements the CRUD actions for Note model.
 */
class NoteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Note models.
     * @return mixed
     */
    public function actionMy()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Note::find()->byCreator(Yii::$app->user->id),
        ]);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        return $this->render('my', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionShared()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Note::find()->byCreator(Yii::$app->user->id)->innerJoinWith(Note::RELATION_ACCESS),
        ]);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];
        return $this->render('shared', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAccessed(){

        $searchModel = new NoteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->onlyAccessed(Yii::$app->user->id);
        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        $userIds = Note::find()->onlyAccessed(Yii::$app->user->id)->groupBy('creator_id')
            ->select('creator_id')->column();
        $users = User::find()->where(['id'=> $userIds])->select('name')->indexBy('id')->column();

        return $this->render('accessed',
            [
                'dataProvider' => $dataProvider,
                'users' => $users,
                'searchModel' => $searchModel,

            ]);
    }


    /**
     * Displays a single Note model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => Note::find()->innerJoinWith('creator')
                ->where(['note.id' => $id])
                ->one()
        ]);
    }

    /**
     * Creates a new Note model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Note();
        $model->creator_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', 'Запись создана');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Note model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', 'Запись изменена');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Note model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('info', 'Запись удалена');
        return $this->redirect(['my']);
    }

    /**
     * Finds the Note model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Note the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Note::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
