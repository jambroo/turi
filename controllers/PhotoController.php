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
use app\models\PhotoNew;
use app\models\PhotoForm;
use app\models\PhotoSearch;
use yii\imagine\Image;

use jambroo\aws\factory\AwsFactory;
use jambroo\aws\factory\S3RenameUploadFactory;

class PhotoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
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
        // Disabling index until galleries implemented
        //return $this->render('index');
        return $this->actionPhotos();
    }

    public function actionPhoto()
    {
        $model = new PhotoForm;

        if (Yii::$app->request->isPost) {
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->image && $model->validate()) {
                $config = Yii::$app->aws;
                // Assuming this is not being run inside Amazon EC2 so it is
                // mandatory for the $config to have 'key' and 'secret' keys
                if (!array_key_exists('key', $config->config) ||
                    !array_key_exists('secret', $config->config) ||
                    !$config->config['key'] ||
                    !$config->config['secret']) {
                    throw new \Exception("Application must be configured with AWS key and AWS secret value.");
                }
                $s3RenameUploadFactory = new S3RenameUploadFactory();
                $s3RenameUpload = $s3RenameUploadFactory->createService($config);

                // Create thumbnail
                // Check if $model->image has file extension
                if (strrpos($model->image, '.') === false) {
                    throw new \Exception("Image must have file extension, e.g. .JPG.");
                }

                try {
                    $thumbnail = tempnam("/tmp", 'turi').substr($model->image, strrpos($model->image, '.'));
                    Image::thumbnail($model->image->tempName, 240, 200)
                        ->save(Yii::getAlias($thumbnail));
                } catch (\Exception $e) {
                    throw new \Exception("Error occurred creating thumbnail of image.");
                }

                // Should store image name ($model->image->name) in db here
                // and use some ID as the filename on s3
                $photo = new Photo;
                $photo->bucket = $config->config['bucket'];
                $photo->image = $model->image->name;
                if (!$photo->save(true, ['bucket', 'image'])) {
                    throw new \Exception("Error uploading image.");
                }

                $s3RenameUpload->setBucket($config->config['bucket']);
                foreach (array('' => $model->image->tempName, '_t' => $thumbnail) as $postfix => $path) {
                    $stream = $s3RenameUpload->getFinalTarget(array(
                        'tmp_name' => $photo->id.$postfix
                    ));

                    $context = stream_context_create(array(
                        's3' => array(
                            'ACL' => 'private'
                        )
                    ));

                    if (file_put_contents($stream, file_get_contents($path), 0, $context) === false) {
                        $photo->delete();
                        throw new \Exception('Error uploading to S3.');
                    }
                }

                // Should redirect to image view page
                return $this->actionShow($photo->id);
            }
        }

        return $this->render('photo', ['model' => $model]);
    }

    public function actionShow($id = false)
    {
        $model = new Photo();
        if ($id === false) {
            $id = Yii::$app->request->getQueryParam('id');
        }

        $photo = $model->findOne($id);
        if (!$photo) {
            return $this->goHome();
        }

        return $this->render('show', [
            'model' => $photo,
            'thumb' => ["url" => $photo->getUrl(), "src" => $photo->getThumb()]
        ]);
    }

    public function actionPhotos()
    {
        $searchModel = new PhotoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $photos = [];
        foreach ($dataProvider->getModels() as $model) {
            $photos []= ['url' => $model->getUrl(),
                         'src' => $model->getThumb()];
        }

        // Should add searchModel and dataProvider here to assist with pagination
        return $this->render('photos', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
            'photos' => $photos
        ]);
    }
}