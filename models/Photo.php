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

  // Helper function to get cloud front link for specific file
  private function getCloudFrontLink($file, $expires = null)
  {
    if ($expires === null) {
      $expires = time() + 10;
    }

    $config = Yii::$app->aws;
    $cloudFrontLinkViewHelperFactory = new CloudFrontLinkViewHelperFactory();
    $cloudFrontLinkViewHelper = $cloudFrontLinkViewHelperFactory->createService($config);

    return $cloudFrontLinkViewHelper->__invoke($file, $config->config['cloudfront-key']['server'], $expires,
      $config->config['cloudfront-key']['key'],
      $config->config['cloudfront-key']['id']
    );
  }

  public function setThumb()
  {
    $this->thumb = $this->getCloudFrontLink($this->id.'_t');
  }

  public function getThumb()
  {
    if (!isset($this->thumb)) {
      $this->setThumb();
    }
    return $this->thumb;
  }

  public function setUrl()
  {
	  $this->url = $this->getCloudFrontLink($this->id);
  }

  public function getUrl()
  {
    if (!isset($this->url)) {
      $this->setUrl();
    }
    return $this->url;
  }

  public function load($data, $formName = null)
  {
  	if (parent::load($data, $formName)) {
      $this->setUrl();
  		$this->setThumb();

  		return true;
  	}
  	return false;
  }
}