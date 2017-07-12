<?php

use yii\db\Migration;

class m170712_181655_leadPerson extends Migration
{
    const TABLE = 'personState';
    const FK = 'fk-pers-persState';
    public $tableFk = 'person';

    public function up()
    {
        $table = $this->tableFk;//m170711_171718_createPerson::TABLE;

        $this->addColumn($table, 'stateId', $this->integer()->null());

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);

        $this->addForeignKey(
            self::FK,
            $table, 'stateId',
            self::TABLE, 'id'
        );

    }

    public function down()
    {
        $this->dropForeignKey(self::FK, $this->tableFk);
        $this->dropColumn($this->tableFk, 'stateId');
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
