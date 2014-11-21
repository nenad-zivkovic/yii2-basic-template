<?php
namespace app\models;

use nenad\passwordStrength\StrengthValidator;
use app\rbac\helpers\RbacHelper;
use yii\base\Model;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * Signup form.
 * -----------------------------------------------------------------------------
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * =========================================================================
     * Returns the validation rules for attributes.
     * =========================================================================
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'unique', 'targetClass' => '\app\models\User', 
                'message' => 'This username has already been taken.'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 
                'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            // password strength rule is determined by StrengthValidator 
            // presets are located in: vendor/nenad/yii2-password-strength/presets.php
            [['password'], StrengthValidator::className(), 'preset'=>'normal']
        ];
    }

    /**
     * =========================================================================
     * Signs up the user. 
     * If scenario is set to "rna" (registration needs activation), this means 
     * that user need to activate his account using email confirmation method.
     * =========================================================================
     *
     * @return User|null              The saved model or null if saving fails.
     * _________________________________________________________________________
     */
    public function signup()
    {
        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->generateAuthKey();

        // first user in system will be activated automatically ( this should be You )
        if ($user->firstUser()) 
        {
            $user->status = User::STATUS_ACTIVE;
        } 
        else 
        {
            $user->generateAccountActivationToken();
            $user->status = User::STATUS_NOT_ACTIVE;
        }
        
        // if user is saved and role is assigned return user object
        return $user->save() && RbacHelper::assignRole($user->getId(), $user->status) ? $user : null;
    }

    /**
     * =========================================================================
     * Sends email to registered user with account activation link.
     * =========================================================================
     *
     * @param  object   $user  Registered user.
     * 
     * @return boolean         Whether the message has been sent successfully.
     * _________________________________________________________________________
     */
    public function sendAccountActivationEmail($user)
    {
        return Yii::$app->mailer->compose('accountActivationToken', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account activation for ' . Yii::$app->name)
            ->send();
    }
}
