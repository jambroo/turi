<?php

use yii\db\Schema;
use yii\db\Migration;

class m150122_101335_create_photo_table extends Migration
{
    public function up()
    {
			$this->createTable('photo', [
				'id' => 'pk',
                'image' => Schema::TYPE_STRING . ' NOT NULL',
                'bucket' => Schema::TYPE_STRING . ' NOT NULL'
			]);
    }

    public function down()
    {
			$this->dropTable('photo');
    }
}
