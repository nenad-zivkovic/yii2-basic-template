<?php
namespace tests\codeception\fixtures;

use yii\test\ActiveFixture;

/**
 * Class UserFixture
 * @package tests\codeception\fixtures
 */
class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User';
}
