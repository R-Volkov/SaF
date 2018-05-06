<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\MyUser;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\MyUser',
                'message' => 'Пользователь с таким адресом не зарегестрирован!'
            ],
            ['email', 'exist',
                'targetClass' => '\common\models\MyUser',
                'filter' => ['status' => MyUser::STATUS_ACTIVE],
                'message' => 'Вы были забанены, ваша учетная запись больше недействительна!'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = MyUser::findOne([
            'status' => MyUser::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!MyUser::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            //->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setFrom(['spinesfan@ukr.net' => 'SaF robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
