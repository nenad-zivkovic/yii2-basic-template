<?php
namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use app\rbac\models\Role;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AppController
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = new User(['scenario' => 'create']);
        $role = new Role();

        if ($user->load(Yii::$app->request->post()) && 
            $role->load(Yii::$app->request->post()) &&
            Model::validateMultiple([$user, $role]))
        {
            $user->setPassword($user->password);
            $user->generateAuthKey();
            
            if ($user->save()) 
            {
                $role->user_id = $user->getId();
                $role->save(); 
            }  

            return $this->redirect(['view', 'id' => $user->id]);      
        } 
        else 
        {
            return $this->render('create', [
                'user' => $user,
                'role' => $role,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $role = Role::findOne(['user_id' => $id]);

        // only The Creator can update everyone`s roles
        // admin will not be able to update role of theCreator
        if (!Yii::$app->user->can('theCreator')) 
        {
            if ($role->item_name === 'theCreator') 
            {
                return $this->goHome();
            }
        }

        $user = $this->findModel($id);

        if ($user->load(Yii::$app->request->post()) && 
            $role->load(Yii::$app->request->post()) && Model::validateMultiple([$user, $role])) 
        {
            // only if user entered new password we want to hash and save it
            if ($user->password) 
            {
                $user->setPassword($user->password);
            }

            $user->save(false);
            $role->save(false); 
            
            return $this->redirect(['view', 'id' => $user->id]);
        }
        else 
        {
            return $this->render('update', [
                'user' => $user,
                'role' => $role,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        // delete this user's role from auth_assignment table
        if ($role = Role::find()->where(['user_id'=>$id])->one()) 
        {
            $role->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
