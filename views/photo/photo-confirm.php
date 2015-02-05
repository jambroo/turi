<?php
use yii\helpers\Html;
?>
<p>You have entered the following information:</p>
<ul>
<li><label>Name</label>: <?= Html::encode($model->name) ?></li>
<li><label>Width</label>: <?= Html::encode($model->width) ?></li>
<li><label>Height</label>: <?= Html::encode($model->height) ?></li>
</ul>
