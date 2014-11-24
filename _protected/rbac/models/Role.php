<?php
namespace app\rbac\models;

use app\models\User;
use yii\db\ActiveRecord;
use Yii;

/**
 * -----------------------------------------------------------------------------
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property User $username
 * -----------------------------------------------------------------------------
 */
class Role extends ActiveRecord
{
    /**
     * =========================================================================
     * Declares the name of the database table associated with this AR class. 
     * =========================================================================
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    /**
     * =========================================================================
     * Returns the validation rules for attributes.
     * =========================================================================
     */
    public function rules()
    {
        return [
            [['item_name'], 'required'],
            [['item_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('app', 'Role'),
        ];
    }

    /**
     * =========================================================================
     * Relation with User class. 
     * =========================================================================
     */
    public function getUser()
    {
        // Role has_many User via User.id -> user_id
        return $this->hasMany(User::className(), ['id' => 'user_id']);
    }    

    /**
     * =========================================================================
     * Returns the username of the Role owner. 
     * NOTE: used in user/update-role view.
     * =========================================================================
     *
     * @return string  Username.
     * _________________________________________________________________________
     */
    public function getUsername()
    {     
        $user = $this->getUser()->one();

        return $user->username;    
    }
}
