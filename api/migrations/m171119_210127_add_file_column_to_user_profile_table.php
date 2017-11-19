<?php

use yii\db\Migration;

/**
 * Handles adding file to table `user_profile`.
 * Has foreign keys to the tables:
 *
 * - `file`
 */
class m171119_210127_add_file_column_to_user_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user_profile', 'file_id', $this->integer()->defaultValue(null));

        // creates index for column `file_id`
        $this->createIndex(
            'idx-user_profile-file_id',
            'user_profile',
            'file_id'
        );

        // add foreign key for table `file`
        $this->addForeignKey(
            'fk-user_profile-file_id',
            'user_profile',
            'file_id',
            'file',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `file`
        $this->dropForeignKey(
            'fk-user_profile-file_id',
            'user_profile'
        );

        // drops index for column `file_id`
        $this->dropIndex(
            'idx-user_profile-file_id',
            'user_profile'
        );

        $this->dropColumn('user_profile', 'file_id');
    }
}
