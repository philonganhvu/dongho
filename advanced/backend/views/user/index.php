<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\imagine\Image;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
             'email:email',
            // 'ancestor_id',
            // 'parent_id',
            // 'left_id',
            // 'right_id',
            // 'is_admin',
             'fullname',
             ['attribute'=>'gender','value'=>function($data){return ($data->gender==1)?'Male':'Female';}],
            // 'married',
            // 'birthYear',
            // 'deathYear',
             'description:ntext',
             'worshipPlace',
             [   'attribute'=>'images',
                 'label'=>'Hình đại diện',
                 'format'=>'raw',
                 'value'=>function($data){
                    return ($data->image)?Html::img('../'.$data->image,['alt'=>'yii']):'';
             }],
            // 'created_at',
            // 'updated_at',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
