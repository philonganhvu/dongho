<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class GraphAsset extends AssetBundle
{
    public $css = [
        'details/awesome/css/font-awesome.min.css',
        'details/awesome/css/details/css/jquery-ui.min.css',
    ];
    public $js = [
        'js/gojs/go.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}
