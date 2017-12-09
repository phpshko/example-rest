<?php

use yii\db\Migration;

/**
 * Class m171120_235646_create_user
 */
class m171120_235646_create_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'            => $this->primaryKey()->unsigned(),
            'email'         => $this->string()->notNull()->unique(),
            'role'          => $this->smallInteger(1)->unsigned()->notNull()->defaultValue(1),
            'password_hash' => $this->string()->notNull(),
            'name'          => $this->string()->notNull(),

            'age'           => $this->smallInteger(1)->unsigned(),
            'gender'        => $this->boolean()->unsigned(),

            'photo_origin_path'  => $this->string(),
            'photo_small_path'   => $this->string(),
            'photo_preview_path' => $this->string(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
