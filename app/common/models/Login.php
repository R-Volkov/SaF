<?php
namespace common\models;

use Yii;
use yii\base\Model;


class Login extends Model {

	public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    public function rules() {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
        ];
    }

    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    protected function updateRemember() {
    		$this->_user->remember = $this->rememberMe;
    		$this->_user->save(false);
    }

    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = MyUser::findByUsername($this->username);
        }
        return $this->_user;
    }

    public function login() {
        if ($this->validate()) {
        	$this->getUser();
        	$this->updateRemember();
        	if ($this->rememberMe == true) {
        		$this->_user->generateAuthKey();
        		$this->_user->save();
        	}
            return Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 5 : 0);
        } else {
            return false;
        }
    }

}