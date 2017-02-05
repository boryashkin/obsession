<?php

use yii\db\Migration;

class m170205_154729_planTimeTrackJunction extends Migration
{
    public function up()
    {
        // @todo: link it to tasks instead, and close tasks automatically on timeTrack ending
        $this->addColumn('timeTrack', 'planId', $this->integer());

        $this->createIndex(
            'idx-ttrack_plan-plan_id',
            'timeTrack',
            'planId'
        );

        $this->addForeignKey(
            'fk-ttrack_plan-plan_id',
            'timeTrack',
            'planId',
            'plan',
            'id',
            'RESTRICT'
        );
    }

    public function down()
    {
        return false;
        $this->dropIndex('fk-ttrack_plan-plan_id', 'timeTrack');
        $this->dropColumn('timeTrack', 'planId');
    }
}
