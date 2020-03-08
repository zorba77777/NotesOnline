<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $surname
 * @property string $password_hash
 * @property string $access_token
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Access[] $accesses
 * @property Note[] $accessedNotes
 * @property Note[] $notes
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    const RELATION_NOTES = 'notes';
    const RELATION_ACCESSES = 'accesses';
    const RELATION_ACCESSED_NOTES = 'accessedNotes';

    public $password;
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
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['username', 'name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['username', 'name', 'surname', 'password', 'access_token', 'auth_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'password_hash' => 'Хэш пароля',
            'access_token' => 'Токен для доступа',
            'auth_key' => 'Ключ авторизации',
            'created_at' => 'Время создания',
            'updated_at' => 'Время редактирования',
        ];
    }


    public function beforeSave($insert){
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->password){
            return $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }

        if ($this->isNewRecord) {
            $this->auth_key = \Yii::$app->security->generateRandomString();

            return true;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesses()
    {
        return $this->hasMany(Access::className(), ['user_id' => 'id']);
    }


    public function getAccessedNotes()
    {
        return $this->hasMany(Access::className(), ['id' => 'note_id'])->
        via(self::RELATION_ACCESSES);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotes()
    {
        return $this->hasMany(Note::className(), ['creator_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\UserQuery(get_called_class());
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }


}
