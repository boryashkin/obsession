<?php

use yii\db\Migration;

class m170220_185127_budgetCategory extends Migration
{
    public function up()
    {
        $this->addColumn('budget', 'categoryId', $this->integer());

        $this->createIndex(
            'idx-budg_cat_cat_id',
            'budget',
            'categoryId'
        );

        $this->addForeignKey(
            'fk-budg_cat-cat_id',
            'budget',
            'categoryId',
            'category',
            'id',
            'RESTRICT'
        );
    }

    public function down()
    {
        return false;
        $this->dropForeignKey('fk-budg_cat-cat_id', 'budget');
        $this->dropColumn('budget', 'categoryId');
        $this->dropTable('category');
        return true;
    }
}
