<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%import}}`.
 */
class m200606_051702_createImportTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%import}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250),
            'code' => $this->bigInteger()->unique(),
            'weight' => $this->float(),
            'usage' => $this->text(),
            'quantity_msk' => $this->integer(),
            'quantity_spb' => $this->integer(),
            'quantity_sam' => $this->integer(),
            'quantity_sar' => $this->integer(),
            'quantity_kaz' => $this->integer(),
            'quantity_nsk' => $this->integer(),
            'quantity_chl' => $this->integer(),
            'quantity_dlch' => $this->integer(),
            'price_msk' => $this->string(100),
            'price_spb' => $this->string(100),
            'price_sam' => $this->string(100),
            'price_sar' => $this->string(100),
            'price_kaz' => $this->string(100),
            'price_nsk' => $this->string(100),
            'price_chl' => $this->string(100),
            'price_dlch' => $this->string(100),
        ]);
        /* Из-за unique и так проиндексируется */
        /*$this->createIndex(
            'idx-code',
            '{{%import}}',
            'code'
        );*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%import}}');
    }
}
