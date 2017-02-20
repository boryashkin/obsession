<?php

use yii\db\Migration;

class m170220_173913_category extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-cat-unique', 'category', ['name'], true);

        //and link cats with operations
        $this->addColumn('operation', 'categoryId', $this->integer());

        $this->createIndex(
            'idx-oper_cat_cat_id',
            'operation',
            'categoryId'
        );

        $this->addForeignKey(
            'fk-oper_cat-cat_id',
            'operation',
            'categoryId',
            'category',
            'id',
            'RESTRICT'
        );
    }

    public function down()
    {
        //return false;
        $this->dropForeignKey('fk-oper_cat-cat_id', 'operation');
        $this->dropColumn('operation', 'categoryId');
        $this->dropTable('category');
        return true;
    }
}
