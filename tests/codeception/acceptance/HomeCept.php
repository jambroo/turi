<?php

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that home page redirs to login');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('Login');