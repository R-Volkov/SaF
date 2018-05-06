<?php 
namespace frontend\models; 

use common\models\MyUser;
use yii\base\Model;


class Signup extends Model {
	public $username;
	public $password;
	public $password2;
	public $remember;
	public $email;

	public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => 'Необходимо указать имя!'],
            ['username', 'unique', 'targetClass' => '\common\models\MyUser', 'message' => 'Это имя уже занято!'],
            ['username', 'string', 'min' => 3, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'Необходимо указать E-mail!'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\MyUser', 'message' => 'Пользователь с таким адресом уже зарегестрирован!'],

            ['password', 'required', 'message' => 'Необходимо указать пароль!'],
            ['password', 'string', 'min' => 6],

            ['password2', 'required', 'message' => 'Необходимо повторить пароль!'],
            ['password2', 'string', 'min' => 6],
            ['password2', 'checkPassword2', 'skipOnEmpty' => false, 'skipOnError' => false],

            [['remember'], 'boolean'],
            [['remember'], 'default', 'value' => true],
        ];
    }

    /**
    * This validator checks whether the second password is equal to the first
    */
    public function checkPassword2($attribute, $params) {
    	if ($this->$attribute != $this->password) {
    		$this->addError($attribute, 'Пароли не совпадают!');
    	}
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new MyUser();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->remember = $this->remember;
        if ($this->remember == true) {
            $user->generateAuthKey();
        }
        
        return $user->save() ? $user : null;
    }

}