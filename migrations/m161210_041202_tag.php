<?php

use yii\db\Migration;

class m161210_041202_tag extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('tag', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-tag-unique', 'tag', ['name'], true);
    }

    public function down()
    {
        $this->dropTable('tag');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
