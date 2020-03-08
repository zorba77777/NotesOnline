<?php

use yii\db\Migration;

/**
 * Class m200308_192245_add_foreign_keys
 */
class m200308_192245_add_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        \Yii::$app->db->createCommand()-> addForeignKey('fx_access_user', 'access', ['user_id'],
            'user', ['id'], 'CASCADE')->execute();
        \Yii::$app->db->createCommand()-> addForeignKey('fx_access_note', 'access', ['note_id'],
            'note', ['id'], 'CASCADE')->execute();
        \Yii::$app->db->createCommand()-> addForeignKey('fx_note_user', 'note', ['creator_id'],
            'user', ['id'], 'CASCADE')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200308_192245_add_foreign_keys cannot be reverted.\n";

        return false;
    }
    */
}
