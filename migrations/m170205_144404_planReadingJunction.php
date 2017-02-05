<?php

use yii\db\Migration;

class m170205_144404_planReadingJunction extends Migration
{
    public function up()
    {
        $this->addColumn('reading', 'planId', $this->integer());

        // creates index for column `operation_id`
        $this->createIndex(
            'idx-read_plan-plan_id',
            'reading',
            'planId'
        );

        // add foreign key for table `operation`
        $this->addForeignKey(
            'fk-read_plan-plan_id',
            'reading',
            'planId',
            'plan',
            'id',
            'RESTRICT'
        );
    }

    public function down()
    {
        return false;
        $this->dropIndex('fk-read_plan-plan_id', 'reading');
        $this->dropColumn('reading', 'planId');
    }
}
