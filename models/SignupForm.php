<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $username;
    public $name;
    public $password_hash;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message'=>'Пожалуйста, введите ваш логин'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Такой пользователь уже существует.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['name', 'trim'],
            ['name', 'required', 'message'=>'Пожалуйста, введите ваше имя'],
            ['password_hash', 'required', 'message'=>'Пожалуйста, введите пароль'],
            ['password_hash', 'string', 'min' => 6, 'message'=>'Минимально 6 символов'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->name = $this->name;
        $user->setPassword($this->password_hash);
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }

}
