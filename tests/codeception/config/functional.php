<?php
$_SERVER['SCRIPT_FILENAME'] = YII_TEST_ENTRY_FILE;
$_SERVER['SCRIPT_NAME'] = YII_TEST_ENTRY_URL;

return yii\helpers\ArrayHelper::merge(
    require(YII_APP_BASE_PATH . '/common/config/main.php'),
    require(YII_APP_BASE_PATH . '/common/config/main-local.php'),
    require(YII_APP_BASE_PATH . 'config/main.php'),
    require(YII_APP_BASE_PATH . 'config/main-local.php'),
    require(__DIR__ . '/config.php'),
    [
      'components' => [
        'request' => [
          'enableCsrfValidation' => false,
        ],
      ],
    ]
);
