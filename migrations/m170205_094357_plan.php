<?php

use yii\db\Migration;

class m170205_094357_plan extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('plan', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->text(),
            'completeness' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->text(),
            'start' => $this->dateTime()->notNull(),
            'duration' => $this->integer()->notNull(),//minutes
            'state' => $this->integer()->notNull()->defaultValue(0),
            'planId' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `operation_id`
        $this->createIndex(
            'idx-task_plan-plan_id',
            'task',
            'planId'
        );

        // add foreign key for table `operation`
        $this->addForeignKey(
            'fk-task_plan-plan_id',
            'task',
            'planId',
            'plan',
            'id',
            'RESTRICT'
        );
    }

    public function down()
    {
        //return false;
        // drops foreign key for table `operation`
        $this->dropForeignKey(
            'fk-task_plan-plan_id',
            'task'
        );
        $this->dropTable('task');
        $this->dropTable('plan');
        return true;
    }
}
