<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "import".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $code
 * @property float|null $weight
 * @property string|null $usage
 * @property int|null $quantity_msk
 * @property int|null $quantity_spb
 * @property int|null $quantity_sam
 * @property int|null $quantity_sar
 * @property int|null $quantity_kaz
 * @property int|null $quantity_nsk
 * @property int|null $quantity_chl
 * @property int|null $quantity_dlch
 * @property string|null $price_msk
 * @property string|null $price_spb
 * @property string|null $price_sam
 * @property string|null $price_sar
 * @property string|null $price_kaz
 * @property string|null $price_nsk
 * @property string|null $price_chl
 * @property string|null $price_dlch
 */
class Import extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'import';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'quantity_msk', 'quantity_spb', 'quantity_sam', 'quantity_sar', 'quantity_kaz', 'quantity_nsk', 'quantity_chl', 'quantity_dlch'], 'integer'],
            [['weight'], 'number'],
            [['usage'], 'string'],
            [['name'], 'string', 'max' => 250],
            [['price_msk', 'price_spb', 'price_sam', 'price_sar', 'price_kaz', 'price_nsk', 'price_chl', 'price_dlch'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'name' => 'Наименование',
            'code' => 'Код',
            'weight' => 'Вес',
            'usage' => 'Взаимозаменяемость',
            'quantity_msk' => 'Кол-во Москва',
            'quantity_spb' => 'Кол-во СПб',
            'quantity_sam' => 'Кол-во Самара',
            'quantity_sar' => 'Кол-во Саратов',
            'quantity_kaz' => 'Кол-во Казань',
            'quantity_nsk' => 'Кол-во Новосибирск',
            'quantity_chl' => 'Кол-во Челябинск',
            'quantity_dlch' => 'Кол-во Д. линии Ч-ск',
            'price_msk' => 'Цена Москва',
            'price_spb' => 'Цена СПб',
            'price_sam' => 'Цена Самара',
            'price_sar' => 'Цена Саратов',
            'price_kaz' => 'Цена Казань',
            'price_nsk' => 'Цена Новосибирск',
            'price_chl' => 'Цена Челябинск',
            'price_dlch' => 'Цена Д. линии Ч-ск',
        ];
    }
}
