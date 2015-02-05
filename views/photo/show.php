<?php
use yii\helpers\Html;
use dosamigos\gallery\Gallery;

/* @var $this yii\web\View */
$this->title = 'Show';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
	<p>
		<label>Image:</label> <?= $model->image ?>
	</p>
  <p>
  	<?= Gallery::widget(['items' => [$thumb]]) ?>
  </p>
</div>
