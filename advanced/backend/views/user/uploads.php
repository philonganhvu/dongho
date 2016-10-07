<?php
/**
 * Created by PhpStorm.
 * User: HuuHai
 * Date: 05/10/2016
 * Time: 10:30 PM
 */

echo \kato\DropZone::widget([
    'options' => [
        'url'=>'index.php?r=user/uploads',
        'maxFilesize' => '2',
    ],
    'clientEvents' => [
        'complete' => "function(file){console.log(file)}",
        'removedfile' => "function(file){alert(file.name + ' is removed')}"
    ],
]);