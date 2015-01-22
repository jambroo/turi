<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class PhotoForm extends Model
{
	public $image;
	
	public function rules()
	{
		return [[['image'], 'file']];
	}
}