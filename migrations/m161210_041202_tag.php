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
        return false;
        $this->dropTable('tag');
        return true;
    }
}
