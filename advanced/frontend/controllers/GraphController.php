<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

/**
 * Site controller
 */
class GraphController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout='//graph.php';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        //$path_to = \Yii::getAlias('@images');//var_dump($path_to);exit;
        $path_to = \Yii::$app->request->BaseUrl;//var_dump($path_to);exit;
        //$this->layout = "@app/views/layouts/graph";
        return $this->render('index', [
            'image_path' => $path_to,
        ]);
    }

    /**
     * Load members  cua dong ho
     */
    public function actionAjaxloadmembers(){
        $md_User = new User();
        $id = Yii::$app->request->post('member_id');
        $depth = Yii::$app->request->post('depth');
        if ($id&&$depth)
            $arrayMembers = $md_User->getMembers($id,$depth);

            $arrayParent = $md_User->getParent($id);
        $data_members = array();
        //all members
        $dataMembers = array();
        if(!empty($arrayParent))
        {
            foreach($arrayParent as $member) {
                $data_members['key'] = $member->id;
                $data_members['name'] = $member->fullname;
                $data_members['gender'] = ($member->gender==1)?'M':'F';
                $data_members['birthYear'] = $member->birthYear;
                $data_members['deathYear'] = $member->deathYear;
                $data_members['spouse'] = $member->spouse;
                $dataMembers[] = $data_members;
            }
        }
        if(!empty($arrayMembers))
        {
            foreach($arrayMembers as $member) {
                $data_members['key'] = $member->id;
                $data_members['parent'] = $member->parent_id;
                $data_members['name'] = $member->fullname;
                $data_members['gender'] = ($member->gender==1)?'M':'F';
                $data_members['birthYear'] = $member->birthYear;
                $data_members['deathYear'] = $member->deathYear;
                $data_members['spouse'] = $member->spouse;
                $dataMembers[] = $data_members;
            }
        }
        echo json_encode($dataMembers);
        Yii::$app->end();
    }
}
