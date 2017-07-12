<?php

use yii\db\Migration;

class m170711_171718_createPerson extends Migration
{
    const TABLE = 'person';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'fullName' => $this->string(),
            'birthdate' => $this->date(),
            'description' => $this->string(),
            'gender' => $this->string(1),// f/m
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        return false;
        echo self::TABLE . ' reverted';
        $this->dropTable(self::TABLE);
    }
}
