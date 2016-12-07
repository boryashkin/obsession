<?php

use yii\db\Migration;

class m161207_180340_operation extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('operation', [
            'id' => $this->primaryKey(),
            'sum' => $this->decimal(10, 2)->notNull(),
            'description' => $this->string(),
            'salary' => $this->boolean()->notNull()->defaultValue(false),
            'creditId' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('credit', [
            'id' => $this->primaryKey(),
            'returned' => $this->boolean()->notNull()->defaultValue(false),
            'dueDate' => $this->date(),
            'creditor' => $this->string()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-credit-oper',
            '{{%operation}}', 'creditId',
            '{{%credit}}', 'id',
            'RESTRICT'
        );
    }

    public function down()
    {
        //$this->dropTable('operations');
        //$this->dropTable('credit');

        //echo 'Проверить, есть ли новости';
        //return false;
    }
}
