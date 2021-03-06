<?php

namespace backend\controllers;

use Yii;
use common\models\Categories;
use common\models\CategoriesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\CategoriesFields;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
          if (Yii::$app->user->isGuest)
            $this->redirect('site/login');
        else
        {
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    }

    /**
     * Displays a single Categories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $categoriesFields = Yii::$app->db->createCommand('select * FROM categories_fields join fields on categories_fields.field_id = fields.field_id WHERE categories_fields.category_id=:category_id');
        $categoriesFields->bindParam(':category_id', $id);
        $categoriesFields->execute();
        $categoriesFields = $categoriesFields->queryAll();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'categoriesFields' => $categoriesFields
        ]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Categories();
        $model->parent_category_id = 0;
        if ($model->load(Yii::$app->request->post()) ) {
            //get the instance of the uploaded file
            $imageName = $model->english_name;
            $model ->file = UploadedFile::getInstance($model,'file');
           if ( isset( $model->file)){
            $model ->file->saveAs('../../frontend/web/images/uploads/'.$imageName.'.'.$model->file->extension );
            //save the path in the db column
            $model ->icon = 'images/uploads/'.$imageName.'.'.$model->file->extension;}
            if ( $model->parent_category_id == Null ){ $model->parent_category_id = 0;}
            $model ->save(false);
            return $this->redirect(['view', 'id' => $model->category_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->category_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
