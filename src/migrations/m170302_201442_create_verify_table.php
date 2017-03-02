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

class m170302_201442_create_verify_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%verify}}', [
            'user_id' => $this->primaryKey(),
            'password_verified_at' => $this->integer(),
            'email_verified_at' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'KEY `user_id` (`user_id`) USING BTREE',
        ], $tableOptions);

        $this->addForeignKey('fk_verify_user_id_user_id', '{{%verify}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%verify}}');
    }
}
