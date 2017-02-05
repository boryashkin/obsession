<?php

use yii\db\Migration;

class m170205_144404_planReadingJunction extends Migration
{
    public function up()
    {
        $this->addColumn('reading', 'planId', $this->integer());

        $this->createIndex(
            'idx-read_plan-plan_id',
            'reading',
            'planId'
        );
        
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
