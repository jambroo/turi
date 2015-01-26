<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

use jambroo\aws\factory\AwsFactory;
use jambroo\aws\factory\S3LinkViewHelperFactory;
use jambroo\aws\factory\CloudFrontLinkViewHelperFactory;

class Photo extends ActiveRecord
{
  /**
   * @inheritdoc
   */
  public static function tableName()
  {
      return 'photo';
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
      return [
          [['image', 'bucket'], 'required'],
          [['image', 'bucket'], 'string', 'max' => 255]
      ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
      return [
          'id' => Yii::t('app', 'ID'),
          'image' => Yii::t('app', 'Image'),
          'bucket' => Yii::t('app', 'Bucket'),
      ];
  }

  public function setUrl()
  {
		// Generate actual S3 Link
		$config = Yii::$app->aws;
	  $cloudFrontLinkViewHelperFactory = new CloudFrontLinkViewHelperFactory();
	  $cloudFrontLinkViewHelper = $cloudFrontLinkViewHelperFactory->createService($config);
	  $expires = time() + 10;

	  $this->url = $cloudFrontLinkViewHelper->__invoke($this->id, $config->config['cloudfront-key']['server'], $expires,
      $config->config['cloudfront-key']['key'],
      $config->config['cloudfront-key']['id']
    );
  }

  public function load($data, $formName = null)
  {
  	if (parent::load($data, $formName)) {
  		$this->setUrl();

  		return true;
  	}
  	return false;
  }
}