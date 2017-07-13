<?php

use yii\db\Migration;

class m170711_173037_note extends Migration
{
    const TABLE = 'interactionNote';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'personId' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'appraisal' => $this->smallInteger(1)->notNull(),// 0 - 9
            'date' => $this->date(),
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->integer()->notNull(),
        ]);

        $personTable = 'person';
        $this->addForeignKey(
            'fk-inter-person',
            '{{%' . self::TABLE . '}}', 'personId',
            "{{%{$personTable}}}", 'id'
        );
    }

    public function down()
    {
        return false;
        echo self::TABLE . ' reverted';
        $this->dropTable(self::TABLE);
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
