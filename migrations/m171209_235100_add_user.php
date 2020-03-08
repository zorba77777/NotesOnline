<?php

use yii\db\Migration;

/**
 * Class m171209_235100_add_user
 */
class m171209_235100_add_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable ( '{{user}}', [
                'id' => $this->primaryKey(),
                'username' => $this->string()->notNull(),
                'name' => $this->string()->notNull(),
                'surname' => $this->string(),
                'password_hash' => $this->string()->notNull(),
                'access_token' => $this->string(),
                'auth_key' => $this->string(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
            ]

        );


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{user}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171209_235100_add_user cannot be reverted.\n";

        return false;
    }
    */
}
