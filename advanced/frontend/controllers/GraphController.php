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
        $name = trim(Yii::$app->request->post('member_name'));
        $id =1;
        if ($name)
            $id = $md_User->getIdbyName($name);

        $depth = Yii::$app->request->post('depth');
        if ($id&&$depth)
            $arrayMembers = $md_User->getMembers($id,$depth);
        $data_members = array();
        //all members
        $dataMembers = array();
        if(!empty($arrayMembers))
        {
            foreach($arrayMembers as $key => $member) {
                if ($key ==0){
                    $data_members['key'] = $member->id;
                    $data_members['name'] = $member->fullname;
                    $data_members['gender'] = ($member->gender==1)?'M':'F';
                    $data_members['birthYear'] = $member->birthYear;
                    $data_members['deathYear'] = $member->deathYear;
                    $data_members['spouse'] = $member->spouse;
                    $dataMembers[] = $data_members;
                }else{
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
        }
        echo json_encode($dataMembers);
        Yii::$app->end();
    }

    /**
     * Load to tien
     */
    public function actionAjaxloadtotien(){
        $md_User = new User();
        $name = trim(Yii::$app->request->post('member_name'));
        $id =0;
        if ($name)
            $id = $md_User->getIdbyName($name);
        if ($id)
            $arrayMembers = $md_User->getTotien($id);
        $data_members = array();
        //all members
        $dataMembers = array();
        if(!empty($arrayMembers))
        {
            foreach($arrayMembers as $key => $member) {
                if ($key ==0){
                    $data_members['key'] = $member->id;
                    $data_members['name'] = $member->fullname;
                    $data_members['gender'] = ($member->gender==1)?'M':'F';
                    $data_members['birthYear'] = $member->birthYear;
                    $data_members['deathYear'] = $member->deathYear;
                    $data_members['spouse'] = $member->spouse;
                    $dataMembers[] = $data_members;
                }else{
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
        }
        echo json_encode($dataMembers);
        Yii::$app->end();
    }

    /**
     * Load to tien
     */
    public function actionAjaxloadquanhe(){
        $md_User = new User();
        $name2 = trim(Yii::$app->request->post('member_name2'));
        if ($name2)
            $id2 = $md_User->getIdbyName($name2);
        if ($id2)
            $arrayMembers2 = $md_User->getTotien($id2);

        //$out2 = array_values($arrayMembers2);

        //file_put_contents('saokochay2.txt', json_encode($out2, JSON_PRETTY_PRINT));

        $name1 = trim(Yii::$app->request->post('member_name1'));
        if ($name1)
            $id1 = $md_User->getIdbyName($name1);
        if ($id1)
            $arrayMembers1 = $md_User->getTotien($id1);
        //Tron 2 day lai
        //$out1 = array_values($arrayMembers1);

        //file_put_contents('saokochay1.txt', json_encode($out1, JSON_PRETTY_PRINT));
        $arrayMembers = array_merge($arrayMembers1,$arrayMembers2);
        $arrayMembers = array_unique($arrayMembers, SORT_REGULAR);

        $data_members = array();
        //all members
        $dataMembers = array();
        if(!empty($arrayMembers))
        {
            foreach($arrayMembers as $key => $member) {
                if ($key ==0){
                    $data_members['key'] = $member->id;
                    $data_members['name'] = $member->fullname;
                    $data_members['gender'] = ($member->gender==1)?'M':'F';
                    $data_members['birthYear'] = $member->birthYear;
                    $data_members['deathYear'] = $member->deathYear;
                    $data_members['spouse'] = $member->spouse;
                    $dataMembers[] = $data_members;
                }else{
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
        }
        //$out = array_values($arrayMembers);

        //file_put_contents('saokochay.txt', json_encode($out, JSON_PRETTY_PRINT));
        echo json_encode($dataMembers);
        Yii::$app->end();
    }
}
