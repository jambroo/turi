<?php

use yii\db\Schema;
use yii\db\Migration;

class m150112_094055_create_user_table extends Migration
{
	public function up()
	{
		$this->createTable('user', [
			'id' => 'pk',
			'username' => Schema::TYPE_STRING . ' NOT NULL',
			'password_hashed' => Schema::TYPE_STRING . ' NOT NULL',
		]);
	}
	
	public function down()
	{
		$this->dropTable('user');
	}
}

/*
CREATE TABLE user (
id INTEGER PRIMARY KEY,
username CHAR(255) NOT NULL,
password_hashed CHAR(64) NOT NULL);
*/