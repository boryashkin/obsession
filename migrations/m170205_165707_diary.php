<?php

use yii\db\Migration;

class m170205_165707_diary extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('diary', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'description' => $this->text()->notNull(),
            'rate' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        return false;
        $this->dropTable('diary');
        return true;
    }
}
