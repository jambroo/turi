<?php

namespace tests\codeception\unit\models;

use Yii;
use yii\codeception\TestCase;
use Codeception\Specify;
use tests\codeception\unit\fixtures\PhotoFixture;
use app\models\Photo;

class PhotoTest extends TestCase
{
    use Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [
            'photo' => [
                'class' => PhotoFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/photo.php'
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->getFixture('photo')->load();
    }

    protected function _before()
    {
        // Before test
    }

    protected function _after()
    {
        // After test
    }

		public function testFindIDFunction()
    {
        $model = new Photo;
        $this->specify('photo model should have findIdentity function', function () use ($model) {
            expect("Photo with ID 1 should exist.", $model->findIdentity(1)->getId())->equals(1);
        });
    }

    public function testURLs()
    {
        $model = new Photo;
        $photo = $model->findIdentity(1);
        $this->specify('photo model should produce URL', function () use ($photo) {
            $url = expect("getUrl should be full URL.", $photo->getUrl());
            $url->contains("https://");
            $url->contains(".cloudfront.net/");
            $url->contains("Expires=");
            $url->contains("Signature=");
            $url->contains("Key-Pair-Id=");

            $url = expect("getThumb should be full URL.", $photo->getThumb());
            $url->contains("https://");
            $url->contains(".cloudfront.net/");
            $url->contains("Expires=");
            $url->contains("Signature=");
            $url->contains("Key-Pair-Id=");
            $url->contains("1_t");
        });
    }
}

