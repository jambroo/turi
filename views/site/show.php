<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Show';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
	<p>
		<label>Bucket:</label> <?= $model->bucket ?>
	</p>
	<p>
		<label>Image:</label> <?= $model->image ?>
	</p>
  <p>
  	<img src="<?= $model->url ?>" />
  </p>
</div>
