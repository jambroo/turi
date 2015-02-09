<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\TestCase;
use Codeception\Specify;
use tests\codeception\unit\fixtures\UserFixture;
use app\models\User;

class UserTest extends TestCase
{
    use Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/user.php'
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->getFixture('user')->load();
    }

    protected function _before()
    {
        // Before test
    }

    protected function _after()
    {
        // After test
    }

    public function testPassword()
    {
        $model = new User;
        $user = $model->findIdentity(1);
        $this->specify('user model should have verifyPassword function', function () use ($user) {
            expect("First user in fixtures should have name 'yazmin37'.", $user->username)->equals("yazmin37");
            expect("First user in fixtures should have password set to 'password_0'.", $user->verifyPassword('password_0'))->true();
        });
            
        $this->specify('user validateCurrentPassword function should create error if invalid password given',
            function () use ($user) {
            $user->currentPassword = "wrong_password";
            $user->validateCurrentPassword();
            expect("Should have error.", $user->getErrors())->equals(['currentPassword' => ['Current password incorrect']]);
        });

        $user = $model->findIdentity(1);
        $this->specify('user validateCurrentPassword function should not create error if vald password given',
            function () use ($user) {
            $user->currentPassword = "password_0";
            $user->validateCurrentPassword();
            expect("Should have no errors.", $user->getErrors())->equals([]);
        });

        $user = $model;
        $this->specify('user beforeSave should create hashed password', function () use ($user) {
            expect("Should have no errors.", $user->password_hashed)->null();
            $user->newPassword = "new_password";
            $user->beforeSave(null);
            expect("Should have no errors.", $user->password_hashed)->notNull();
        });
    }

    public function testFindFunctions()
    {
        $model = new User;
        $this->specify('user model should have find by functions', function () use ($model) {
            expect("User with name 'yazmin37' should exist.", $model->findByUsername('yazmin37')->getId())->equals(1);
            expect("User with ID 1 should exist.", $model->findIdentity(1)->getId())->equals(1);
        });
    }
}

