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

	public function validate()
	{
		$finfoMimeType = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
		if (!$this->image->tempName) {
			$maxUploadMb = $this->getMaxUploadMb();
			$this->addError('image', \sprintf('Error uploading file. Please ensure file is under %d megabyte%s.',
				$maxUploadMb, ($maxUploadMb > 1)?'s':''));
			return false;
		}
		$finfo = finfo_file($finfoMimeType, $this->image->tempName);
		finfo_close($finfoMimeType);

		if ($finfo && (strlen($finfo) > 6) && (substr($finfo, 0, 6) == 'image/')) {
			return true;
		}

		$this->addError('image', 'Invalid file type. Image file must be uploaded.');
		return false;
	}

	// Function 'borrowed' from http://stackoverflow.com/a/22500394/3799217
	private function convertPHPSizeToBytes($sSize)
	{
		if (is_numeric($sSize)) {
			return $sSize;
		}

		$sSuffix = substr($sSize, -1);
		$iValue = substr($sSize, 0, -1);

		switch(strtoupper($sSuffix)){
		case 'P':
			$iValue *= 1024;
		case 'T':
			$iValue *= 1024;
		case 'G':
			$iValue *= 1024;
		case 'M':
			$iValue *= 1024;
		case 'K':
			$iValue *= 1024;
			 break;
		}
		return $iValue;
	}

	private function getMaxUploadMb()
	{
		return (min($this->convertPHPSizeToBytes(ini_get('post_max_size')),
		$this->convertPHPSizeToBytes(ini_get('upload_max_filesize'))))/1024/1024;
	}
}