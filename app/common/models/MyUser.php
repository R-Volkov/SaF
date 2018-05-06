<?php 
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;
use common\MyHelpers\ImageBuilder;
use common\models\Comments;
use common\models\UserSocialNetworks;


class MyUser extends ActiveRecord implements IdentityInterface {

	const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_SUPERADMIN = 'superadmin';

    public $old_password;
    public $new_password1;
    public $new_password2;
    public $raw_userpic;
    public $comment_count;
    protected $old_userpic;

    public static function tableName() {
    	return '{{users}}';
    }

    public function rules() {
    	return [
    		[['status'], 'default', 'value' => self::STATUS_ACTIVE],
    		[['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
    		[['role'], 'default', 'value' => self::ROLE_USER],
    		[['role'], 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN, self::ROLE_MODERATOR, self::ROLE_SUPERADMIN]],
            [['old_password'], 'validateOldPassword'],
            [['new_password1', 'new_password2'], 'validateNewPassword'],
            [['email'], 'email'],
            [['raw_userpic'], 'safe'],
            [['userpic'], 'safe'],
            [['username', 'access_token'], 'string'],
    	];
    }

    // public function getNetworks()
    // {
    //     return $this->hasMany(UserSocialNetworks::className(), ['user_id' => 'id']);
    // }

    public function getComment()
    {
        return $this->hasMany(Comments::className(), ['user_id' => 'id']);
    }

    public function validateOldPassword($attribute, $params) {
        if (($this->old_password != false) || ($this->new_password1 != false) || ($this->new_password2 != false)) {
            if ($this->validatePassword($this->old_password) == false) {
                $this->addError($attribute, 'Вы не правильно ввели текущий пароль!');
            }
        }        
    }

    public function validateNewPassword($attribute, $params) {
        if (($this->old_password != false) || ($this->new_password1 != false) || ($this->new_password2 != false)) {
            if ($this->new_password1 !== $this->new_password2) {
                $this->addError($attribute, 'Пароли не совпадают!');
            }
        }        
    }

    public static function findIdentity($id) {
    	return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public function getId() {
    	return $this->getPrimaryKey();
    }

    public function getAuthKey() {
    	return $this->auth_key;
    }

    public function getRole() {
    	return $this->role;
    }

    public function validateAuthKey($authKey) {
    	return $this->getAuthKey() === $authKey;
    }

    public function setPassword($password){
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    

    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    protected function changePassword()
    {
        if (($this->old_password == false) && ($this->new_password1 == false) && ($this->new_password2 == false)) {
            return NULL;
        }
        if ($this->validatePassword($this->old_password) && ($this->new_password1 === $this->new_password2)) {
            $this->setPassword($this->new_password1);
        } else {
            return false;
        }
        return true;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if (!($this->isNewRecord)) {
            $this->changePassword();
        }
        //$this->upload();
        return true;
    }

    public function afterFind()
    {
        $this->old_userpic = $this->userpic;
    }

    public function upload()
    {
        if ($this->raw_userpic != false) {
            //Загрузка нового юзерпика
            $userpic_path = Yii::getAlias('@absolute_uploads') . '/USERS//';
            $this->raw_userpic->name = time() . '_' . Yii::$app->getSecurity()->generateRandomString(8) . '.' . $this->raw_userpic->extension;
            $this->raw_userpic->saveAs($userpic_path . $this->raw_userpic->name);
            $this->userpic = $this->raw_userpic->name;

            //Загрузка новой миниатюры юзерпика
            ImageBuilder::createMiniature(
                $this->raw_userpic->name,
                'small_' . $this->raw_userpic->name,
                $this->raw_userpic->extension, 
                $userpic_path, 
                200
            );

            //Удаление прежнего юзерпика
            if (($this->old_userpic != false) && (file_exists($userpic_path . $this->old_userpic))) {
                unlink($userpic_path . $this->old_userpic);
            }
            if (($this->old_userpic != false) && (file_exists($userpic_path . 'small_' . $this->old_userpic))) {
                unlink($userpic_path . 'small_' . $this->old_userpic);
            }
        }
        return true;
    }

}