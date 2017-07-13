<?php

use yii\db\Migration;

class m170713_174938_contact extends Migration
{
    const TABLE = 'contact';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'personId' => $this->integer(),
            'contact' => $this->string(),
            'type' => $this->string(),
            'note' => $this->string()->null(),
            'sort' => $this->integer()->defaultValue(10),
        ]);

        $personTable = 'person';
        $this->addForeignKey(
            'fk-cont-person',
            '{{%' . self::TABLE . '}}', 'personId',
            "{{%{$personTable}}}", 'id'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-cont-person', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
