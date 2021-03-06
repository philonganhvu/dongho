<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $p_parent = $model->parent_id;
            $p_fullname = $model->fullname;
            $imageName = $p_name = $model->username;
            $model->file = UploadedFile::getInstance($model,'file');
            $model->file->saveAs('uploads/'.$imageName.'.'.'jpg');
            $p_image = 'uploads/'.$imageName.'.'.'jpg';
            if ($model->addMember($p_parent,$p_name,$p_fullname,$p_image))
                return $this->redirect(['view', 'id' => $model->id]);
            else
                return;
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $imageName = $model->username;
            $model->file = UploadedFile::getInstance($model,'file');
            $model->file->saveAs('uploads/'.$imageName.'.'.'jpg');
            $model->image = 'uploads/'.$imageName.'.'.'jpg';
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            else
                return;
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return bool
     */
    public function actionUploads()
    {
        $fileName = 'file';
        $uploadPath = 'uploads/';

        if (isset($_FILES[$fileName])) {
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);

            //Print file data
            //print_r($file);exit;

            if ($file->saveAs($uploadPath . '/' . $file->name)) {
                //Now save file data to database

                //echo \yii\helpers\Json::encode($file);
                echo $uploadPath . '/' . $file->name;
            }
        }else{
            return $this->render('uploads');
        }

        return false;
    }

    /**
     * @return bool
     */
    public function actionDeletefiles()
    {
        if (Yii::$app->request->post()){
            $fileName = Yii::$app->request->post('id');
            file_put_contents('duongdan.txt','duong dan '.$fileName);
            $uploadPath = 'uploads';
            if (isset($fileName)) {
                file_put_contents('duongdan.txt',$uploadPath.DIRECTORY_SEPARATOR.$fileName);
                unlink($uploadPath.DIRECTORY_SEPARATOR.$fileName); //delete it

            }
        }
        return false;
    }
}
