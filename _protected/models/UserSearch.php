<?php
namespace app\models;

use app\rbac\models\AuthItem;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use Yii;

/**
 * ------------------------------------------------------------------------------
 * UserSearch represents the model behind the search form for `app\models\User`.
 * ------------------------------------------------------------------------------
 */
class UserSearch extends User
{
    /**
     * How many users we want to display per page.
     * 
     * @var integer
     */
    private $_pageSize = 11;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'status', 'item_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // we make sure that admin can not see users with theCreator role
        if (!Yii::$app->user->can('theCreator')) 
        {
            $query = User::find()->joinWith('role')
                                 ->where(['!=', 'item_name', 'theCreator']);
        }
        else
        {
            $query = User::find()->joinWith('role');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => $this->_pageSize,
            ]
        ]);

        // make item_name (Role) sortable
        $dataProvider->sort->attributes['item_name'] = [
            'asc' => ['item_name' => SORT_ASC],
            'desc' => ['item_name' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['like', 'item_name', $this->item_name]);

        return $dataProvider;
    }

    /**
     * Returns the array of possible user roles.
     * 
     * @return array
     */
    public static function rolesList()
    {
        foreach (AuthItem::getRoles() as $item_name) 
        {
            $roles[$item_name->name] = $item_name->name;
        }

        return $roles;
    }
}
