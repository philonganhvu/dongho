<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo '<!--';var_dump($this->title);echo '-->';
?>
<style>
    .jumbotron {
        background-color: #f4511e;
        color: #fff;
        padding: 100px 25px;
    }
    .container-fluid {
        padding: 60px 50px;
    }
    .bg-grey {
        background-color: #f6f6f6;
    }
    .logo-small {
        color: #f4511e;
        font-size: 50px;
    }
    .logo {
        color: #f4511e;
        font-size: 200px;
    }
    .thumbnail {
        padding: 0 0 15px 0;
        border: none;
        border-radius: 0;
    }
    .thumbnail img {
        width: 100%;
        height: 100%;
        margin-bottom: 10px;
    }
    .carousel-control.right, .carousel-control.left {
        background-image: none;
        color: #f4511e;
    }
    .carousel-indicators li {
        border-color: #f4511e;
    }
    .carousel-indicators li.active {
        background-color: #f4511e;
    }
    .item h4 {
        font-size: 19px;
        line-height: 1.375em;
        font-weight: 400;
        font-style: italic;
        margin: 70px 0;
    }
    .item span {
        font-style: normal;
    }
    .panel {
        border: 1px solid #f4511e;
        border-radius:0 !important;
        transition: box-shadow 0.5s;
    }
    .panel:hover {
        box-shadow: 5px 0px 40px rgba(0,0,0, .2);
    }
    .panel-footer .btn:hover {
        border: 1px solid #f4511e;
        background-color: #fff !important;
        color: #f4511e;
    }
    .panel-heading {
        color: #fff !important;
        background-color: #f4511e !important;
        padding: 25px;
        border-bottom: 1px solid transparent;
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
    }
    .panel-footer {
        background-color: white !important;
    }
    .panel-footer h3 {
        font-size: 32px;
    }
    .panel-footer h4 {
        color: #aaa;
        font-size: 14px;
    }
    .panel-footer .btn {
        margin: 15px 0;
        background-color: #f4511e;
        color: #fff;
    }
    .padding_left{
        margin-right: 85px;
    }
    .padding_right{
        margin-left: 85px;
    }
    @media screen and (max-width: 768px) {
        .col-sm-5 {
            text-align: center;
            margin: 25px 0;
        }
    }
</style>
<div class="container-fluid bg-grey user-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
    ]) ?>
    <!--adding a hint and a customized label-->
    <div class="row">
        <div class="padding_left col-sm-5"><?= $form->field($model, 'username')->textInput()->label('Name') ?></div>
        <div class="padding_right col-sm-5"><?= $form->field($model, 'email')->input('email')->label('Mail') ?></div>
    </div>

    <!--creating a HTML5 email input element-->
    <div class="row">
        <div class="padding_left col-sm-5"><?= $form->field($model, 'email')->input('email') ?></div>
        <div class="padding_right col-sm-5"><?= $form->field($model, 'email')->input('email') ?></div>
    </div>

    <div class="form-group">
        <?= 'Many pictures'?>
    </div>
    <?= \kato\DropZone::widget([
        'options' => [
            'url'=>'index.php?r=user/uploads',
            'maxFilesize' => '2',
        ],
        'clientEvents' => [
            'complete' => "function(file){upload_files(file.name)}",
            'removedfile' => "function(file){deleteImage_Click(file);}"
        ],
    ]); ?>
    <input type="hidden" id="uploads_file" name="uploads_file" value="">
    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script type="text/javascript">
    /*$('#myDropzone').addClass('form-group');*/
    var d = document.getElementById("myDropzone");
    d.className += " form-group";
    /**
     * get name to save databases
     * @param path_files
     */
    function upload_files(path_files){
        document.getElementById("uploads_file").value += path_files+', ';
    };

    function deleteImage_Click(image_name) {
        var strconfirm = confirm("Are you sure you want to delete "+image_name.name+" ?");
        if (strconfirm == true) {
            removedfile(image_name);
            var string_images = document.getElementById("uploads_file").value;console.log(string_images);
            var file_remove = image_name.name+', ';
            document.getElementById("uploads_file").value = string_images.replace(file_remove,'');
        }
    }

    /**
     * remove file
     */
    function removedfile(file) {
        var name = file.name;
        $.ajax({
            type: 'POST',
            url: 'index.php?r=user/deletefiles',
            data: "id="+name,
            dataType: 'html'
        });
    }
</script>
