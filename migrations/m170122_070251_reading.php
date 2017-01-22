<?php

use yii\db\Migration;

class m170122_070251_reading extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('reading', [
            'id' => $this->primaryKey(),
            'category' => $this->string()->notNull(),
            'name' => $this->string(),
            'link' => $this->text(),
            'done' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('reading');
        return true;
    }
}
