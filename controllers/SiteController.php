<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Photo;
use app\models\PhotoForm;

use jambroo\aws\factory\AwsFactory;
use jambroo\aws\factory\S3RenameUploadFactory;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPhoto()
    {
        $model = new PhotoForm;

        if (Yii::$app->request->isPost) {
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->image && $model->validate()) {
                $config = Yii::$app->aws;
                $s3RenameUploadFactory = new S3RenameUploadFactory();
                $s3RenameUpload = $s3RenameUploadFactory->createService($config);

                // Should store image name ($model->image->name) in db here
                // and use some ID as the filename

                $s3RenameUpload->setBucket($config->config['bucket']);
                $stream = $s3RenameUpload->getFinalTarget(array(
                    'tmp_name' => time()
                ));

                if (file_put_contents($stream, file_get_contents($model->image->tempName)) === false) {
                    throw new \Exception('Error uploading to S3.');
                }

                // Should redirect to image view page
                return $this->goHome();
            }
        }

        return $this->render('photo', ['model' => $model]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionShow() {
        $model = new Photo();
        if (!$model->load(array('Photo' => Yii::$app->request->get()))) {
            return $this->goHome();
        }

        return $this->render('show', [
            'model' => $model
        ]);
    }    
}