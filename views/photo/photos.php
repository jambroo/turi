<?php

use yii\helpers\Html;
use dosamigos\gallery\Gallery;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Photos');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="photo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= Gallery::widget(['items' => $photos]) ?>
</div>
