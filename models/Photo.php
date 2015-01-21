<?php
namespace app\models;

use Yii;
use yii\base\Model;

use jambroo\aws\factory\AwsFactory;
use jambroo\aws\factory\S3LinkViewHelperFactory;

class Photo extends Model
{
	public $bucket;
	public $image;
	public $url;
	
	public function rules()
	{
		return [[['bucket', 'image'], 'required']];
	}

  public function load($data, $formName = null)
  {
  	if (parent::load($data, $formName)) {
  		$this->generateUrl();
  		return true;
  	}
  	return false;
  }
	
	private function generateUrl()
	{
		$config = Yii::$app->aws;
	  $s3LinkViewHelperFactory = new S3LinkViewHelperFactory();
	  $s3LinkViewHelper = $s3LinkViewHelperFactory->createService($config);
	  $expires = time() + 10;

	  $this->url = $s3LinkViewHelper->__invoke($this->image, $this->bucket, $expires);
	}
}