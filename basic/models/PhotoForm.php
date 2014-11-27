<?php
namespace app\models;

use yii\base\Model;

class PhotoForm extends Model
{
	public $name;
	public $width;
	public $height;
	
	public function rules()
	{
		return [[['name', 'width', 'height'], 'required']];
	}
}