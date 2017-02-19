<?php

use yii\db\Migration;

class m170219_154201_operationBudget extends Migration
{
    public function up()
    {
        $this->addColumn('operation', 'budgetId', $this->integer());

        $this->createIndex(
            'idx-oper_budget-bud_id',
            'operation',
            'budgetId'
        );

        $this->addForeignKey(
            'fk-oper_budget-plan_id',
            'operation',
            'budgetId',
            'budget',
            'id',
            'RESTRICT'
        );
    }

    public function down()
    {
        return false;
        $this->dropIndex('fk-oper_budget-plan_id', 'operation');
        $this->dropColumn('operation', 'budgetId');
    }
}
