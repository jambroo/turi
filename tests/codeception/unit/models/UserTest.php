<?php

namespace tests\codeception\unit\models;

use yii\codeception\TestCase;
use Codeception\Specify;
use tests\codeception\unit\fixtures\UserFixture;

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
        $this->loadFixtures(['user']);
    }

    protected function _before()
    {
        print_r('sup_before'."\n");
    }

    protected function _after()
    {
        print_r('sup_after'."\n");
    }

    // tests
    public function testMe()
    {
        $model = 1;
        $this->specify('user should not be able to login, when there is no identity', function () use ($model) {
            expect('model should not login user', true)->false();
        });
    }

}

