<?php

use yii\db\Migration;

class m161211_103938_timeTrack extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('activity', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-activ-unique', 'activity', ['name'], true);

        $this->createTable('timeTrack', [
            'id' => $this->primaryKey(),
            'activityId' => $this->integer()->notNull(),
            'start' => $this->dateTime()->notNull(),
            'stop' => $this->dateTime(),
            'note' => $this->string(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-timet-activ',
            '{{%timeTrack}}', 'activityId',
            '{{%activity}}', 'id',
            'RESTRICT'
        );
    }

    public function down()
    {
        return false;
        $this->dropTable('timeTrack');
        $this->dropTable('activity');
        return true;
    }
}
