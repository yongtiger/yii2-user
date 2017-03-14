<?php ///[Yii2 uesr]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

use yii\db\Migration;

class m170302_201442_create_user_oauth_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_oauth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'provider' => $this->string()->notNull(),
            'openid' => $this->string()->notNull(),
            'email' => $this->string(),
            'fullname' => $this->string(),
            'firstname' => $this->string(),
            'lastname' => $this->string(),
            'gender' => $this->smallInteger(1),
            'language' => $this->string(),
            'avatar' => $this->string(),
            'link' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'UNIQUE KEY `account_unique` (`provider`,`openid`) USING BTREE',
            'KEY `user_id_fk` (`user_id`) USING BTREE',
        ], $tableOptions);

        $this->addForeignKey('user_id_fk', '{{%user_oauth}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%user_oauth}}');
    }
}
