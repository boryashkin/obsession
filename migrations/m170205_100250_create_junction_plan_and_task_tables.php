<?php

use yii\db\Migration;

/**
 * Handles the creation of table `plan_task`.
 * Has foreign keys to the tables:
 *
 * - `plan`
 * - `task`
 */
class m170205_100250_create_junction_plan_and_task_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('plan_task', [
            'plan_id' => $this->integer(),
            'task_id' => $this->integer(),
            'PRIMARY KEY(plan_id, task_id)',
        ]);

        // creates index for column `plan_id`
        $this->createIndex(
            'idx-plan_task-plan_id',
            'plan_task',
            'plan_id'
        );

        // add foreign key for table `plan`
        $this->addForeignKey(
            'fk-plan_task-plan_id',
            'plan_task',
            'plan_id',
            'plan',
            'id',
            'CASCADE'
        );

        // creates index for column `task_id`
        $this->createIndex(
            'idx-plan_task-task_id',
            'plan_task',
            'task_id',
            true
        );

        // add foreign key for table `task`
        $this->addForeignKey(
            'fk-plan_task-task_id',
            'plan_task',
            'task_id',
            'task',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `plan`
        $this->dropForeignKey(
            'fk-plan_task-plan_id',
            'plan_task'
        );

        // drops index for column `plan_id`
        $this->dropIndex(
            'idx-plan_task-plan_id',
            'plan_task'
        );

        // drops foreign key for table `task`
        $this->dropForeignKey(
            'fk-plan_task-task_id',
            'plan_task'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            'idx-plan_task-task_id',
            'plan_task'
        );

        $this->dropTable('plan_task');
    }
}
