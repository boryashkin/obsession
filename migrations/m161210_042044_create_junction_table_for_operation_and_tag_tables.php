<?php

use yii\db\Migration;

/**
 * Handles the creation of table `operation_tag`.
 * Has foreign keys to the tables:
 *
 * - `operation`
 * - `tag`
 */
class m161210_042044_create_junction_table_for_operation_and_tag_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('operation_tag', [
            'operation_id' => $this->integer(),
            'tag_id' => $this->integer(),
            'PRIMARY KEY(operation_id, tag_id)',
        ]);

        // creates index for column `operation_id`
        $this->createIndex(
            'idx-operation_tag-operation_id',
            'operation_tag',
            'operation_id'
        );

        // add foreign key for table `operation`
        $this->addForeignKey(
            'fk-operation_tag-operation_id',
            'operation_tag',
            'operation_id',
            'operation',
            'id',
            'RESTRICT'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            'idx-operation_tag-tag_id',
            'operation_tag',
            'tag_id'
        );

        // add foreign key for table `tag`
        $this->addForeignKey(
            'fk-operation_tag-tag_id',
            'operation_tag',
            'tag_id',
            'tag',
            'id',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `operation`
        $this->dropForeignKey(
            'fk-operation_tag-operation_id',
            'operation_tag'
        );

        // drops index for column `operation_id`
        $this->dropIndex(
            'idx-operation_tag-operation_id',
            'operation_tag'
        );

        // drops foreign key for table `tag`
        $this->dropForeignKey(
            'fk-operation_tag-tag_id',
            'operation_tag'
        );

        // drops index for column `tag_id`
        $this->dropIndex(
            'idx-operation_tag-tag_id',
            'operation_tag'
        );

        $this->dropTable('operation_tag');
    }
}
