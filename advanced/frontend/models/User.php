<?php

namespace app\models;

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
 * @property integer $married
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
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['ancestor_id', 'parent_id', 'left_id', 'right_id', 'is_admin', 'gender', 'married', 'birthYear', 'deathYear', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'fullname', 'worshipPlace', 'image'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
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
            'gender' => Yii::t('app', 'Gender'),
            'married' => Yii::t('app', 'Married'),
            'birthYear' => Yii::t('app', 'Birth Year'),
            'deathYear' => Yii::t('app', 'Death Year'),
            'description' => Yii::t('app', 'Description'),
            'worshipPlace' => Yii::t('app', 'Worship Place'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @param $Id
     * @param $depth
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMembers($Id,$depth){
        $sql = "call user_get_branch(".$Id.",".$depth.",'--');";
        //file_put_contents('loadVideos.txt',json_encode($arrayMembers));
        file_put_contents('loadmembers.txt',$sql);
        $members = User::findBySql($sql, [])->all();
        file_put_contents('allmembers.txt',json_encode($members));
        return $members;
    }


    public function getParent($Id){
        $sql = "SELECT id
                 , fullname
                 , gender
                 , description
                 , ancestor_id
            FROM user
            WHERE id = $Id";
        $members = User::findBySql($sql, [])->all();
        return $members;
    }
}
