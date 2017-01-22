<?php

use yii\db\Migration;

class m170122_075414_budget extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('budget', [
            'id' => $this->primaryKey(),
            'firstExpectedDate' => $this->date()->notNull(),
            'expectedDate' => $this->date()->notNull(),
            'realDate' => $this->date(),
            'name' => $this->string(),
            'description' => $this->text(),
            'expectedSum' => $this->decimal(10, 2)->notNull(),
            'realSum' => $this->decimal(10, 2),
            'done' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        return false;
        $this->dropTable('budget');
        return true;
    }
}
