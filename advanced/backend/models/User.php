<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $ancestor_id
 * @property integer $parent_id
 * @property integer $left_id
 * @property integer $right_id
 * @property integer $is_admin
 * @property string $fullname
 * @property integer $gender
 * @property integer $spouse
 * @property integer $birthYear
 * @property integer $deathYear
 * @property string $description
 * @property string $worshipPlace
 * @property string $image
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $file;
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['ancestor_id', 'parent_id', 'left_id', 'right_id', 'is_admin', 'gender', 'spouse', 'birthYear', 'deathYear', 'status'], 'integer'],
            [['description'], 'string'],
            [['file'], 'file'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'fullname', 'worshipPlace', 'image'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'ancestor_id' => Yii::t('app', 'Ancestor ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'left_id' => Yii::t('app', 'Left ID'),
            'right_id' => Yii::t('app', 'Right ID'),
            'is_admin' => Yii::t('app', 'Is Admin'),
            'fullname' => Yii::t('app', 'Fullname'),
            'gender' => Yii::t('app', 'gender'),
            'spouse' => Yii::t('app', 'spouse'),
            'birthYear' => Yii::t('app', 'Birth Year'),
            'deathYear' => Yii::t('app', 'Death Year'),
            'description' => Yii::t('app', 'Description'),
            'worshipPlace' => Yii::t('app', 'Worship Place'),
            'image' => Yii::t('app', 'Image'),
            'file' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    public function addMember($p_parent,$p_name,$p_fullname,$p_image){
        $sql = 'call user_add_node('.$p_parent.',"'.$p_name.'","'.$p_fullname.'","'.$p_image.'");';
        $connection = \Yii::$app->db;
        //file_put_contents('user_add_node.sql', $sql);
        $connection->createCommand($sql)->execute();
        return true;
    }
}
