<?php
/**
 * Тестовое задание
 * 2020-06-06
 * Сергей Д. aka webgeopro
 */

namespace app\commands;

use app\models\Import;
use yii\console\Controller;

class XmlController extends Controller
{
    /** Директория с файлами данных */
    protected const DIR = './data';

    /** Шаблон имени файла */
    protected const TPL_IMPORT = '/import[0-9]+_1.xml/';
    protected const TPL_IMPORT_NUM = '/import([0-9]+)_1.xml/';
    protected const TPL_JSON = '/[0-9]{8}_([0-9])+.json/';

    /** Поскольку города жестко прописаны в БД, создаем массив подстановок №<=>сокращение */
    public const CITIES = ['msk', 'spb', 'sam', 'sar', 'kaz', 'nsk', 'chl', 'dlch'];

    /**
     * Default Action (yii xml)
     */
    public function actionIndex()
    {
        echo "\n"
            . "Консольное приложение импорта данных."
            . "\nДла работы наберите:"
            . "\n  - yii xml/import [для импорта данных из всех файлов]"
            . "\n  - yii xml/save [для сохранения в БД]"
            //. "\n\n- yii xml/all [импорт + сохранение]"
            . "\n";
        return 0;
    }
    /**
     * Обход папки с xml-файлами
     */
    public function actionImport()
    {
        echo "Этап 1. Сохранение на диск данных из xml-файлов.";
        $files = $this->iterate();
        foreach ($files as $cityID => $file) {
            echo "\nКод города: " . $cityID;
            echo "\nПарсинг xml-файла.";
            $items = $this->parse($file);
            echo "\nСохранение сведенного файла в папке json";
            echo $this->saveDisk($items, $cityID) ? ' :: успешно.' : ' :: произошла ошибка.';
        }
        echo "\n\nЭтап 1 Завершен.";
    }

    /**
     * Сохранение в БД из json-файлов
     */
    public function actionSave()
    {
        echo "Этап 2. Сохранение в БД.";
        $dir = new \DirectoryIterator(self::DIR . '/json/');
        $files = new \RegexIterator($dir, self::TPL_JSON); // Отсеиваем не подходящие по шаблону
        $errors = [];

        foreach ($files as $fileName) {
            preg_match(self::TPL_JSON, $fileName, $cityID); // Получаем номер города
            $cityName = self::CITIES[$cityID[1]];
            if ('' == $cityName) {
                echo('Ошибка определения города для '.$cityID[1]);
                continue;
            }
            $file = file_get_contents(self::DIR . '/json/' . $fileName);
            $items = json_decode($file, true);

            /* Вставка в БД используя модель AR */
            if ($items) {
                foreach ($items['items'] as $item) {
                    $itemCode = (integer)$item['code'];
                    if (0 < $itemCode) {
                        $one = Import::find()->where(['code' => $itemCode])->limit(1)->one();
                        if (null == $one) {
                            $one = new Import();
                            $one->name = $item['name'];
                            $one->code = $itemCode;
                            $one->weight = $item['weight'];
                            $one->usage = $item['usage'];
                        }
                        $one['price_'.$cityName] = $item['price'];
                        $one['quantity_'.$cityName] = $item['quantity'];
                        if (!$one->save()) {
                            $errors[]= ['item' => (array)$item, 'mess' => $one->errors];
                        }
                    } else {
                        $errors[] = ['item' => $item, 'mess' => 'Ошибка: не указано поле код'];
                    }
                }
                echo "\nФайл {$fileName} обработан.";
            } else echo "\nError parse ". $cityID[0]; //todo Exception
        }
        if ($errors) {
            print_r($errors);
        }
        echo "\nЭтап 2. Завершено.";
    }

    /**
     * Обход папки с данными, формируем список файлов
     * * @return array files
     */
    protected function iterate()
    {
        $dir = new \DirectoryIterator(self::DIR);
        $files = new \RegexIterator($dir, self::TPL_IMPORT); // Отсеиваем не подходящие по шаблону
        $res = [];
        foreach ($files as $key=>$file) {
            $cityID = null; // Номер города из названия файла-импорта
            $importName = $file->getFilename(); // Название файла-импорта
            preg_match(self::TPL_IMPORT_NUM, $importName, $cityID); // Получаем номер города
            $cityID = $cityID[1];
            $res[$cityID]['import'] = $importName;
            $offerName = 'offers' . $cityID. '_1.xml';
            if (file_exists(self::DIR . '/' . $offerName)) {
                $res[$cityID]['offer'] = $offerName;
            }
        }
        return $res;
    }

    /**
     * Парсинг содержимого XML-файла
     * @param $file
     * @return array
     */
    protected function parse($file)
    {
        $res = [];
        $import = simplexml_load_file(self::DIR . '/' . $file['import']);
        $offer = simplexml_load_file(self::DIR . '/' . $file['offer']);
        $res['city'] = (string)$import->Классификатор->Наименование;

        /*var_export($offer->xpath(//Не работает
            "Предложение[Ид='a44e48b4-9b28-11e4-80ba-000c29ae6a43']"));
        var_dump($offer->xpath(
            'Предложение[Код=305540]'));
        var_dump($offer->xpath(
            '//Предложение[Артикул="LF3349"]'));*/
        // Формируем очищенный массив значений из xml-import файлов
        foreach ($import->Каталог->Товары->Товар as $item) {
            $usage = [];
            // Объединяем поля из тега Взаимозанимаемость(Марка-Модель-Категория)
            if (isset($item->Взаимозаменяемости)) {
                foreach ($item->Взаимозаменяемости as $obj) {
                    $usage[] = implode('-', (array)$obj->Взаимозаменяемость);
                }
            }
            $res['items'][(string)$item->Ид] = [
                'id' => (string)$item->Ид,
                'name' => (string)$item->Наименование,
                'code' => (int)$item->Код,
                'weight' => (float)$item->Вес,
                'usage' => implode('|', $usage),
                'quantity' => 0,
                'price' => 0,
            ];
        }
        // Дополняем массив полями количество и цена  из xml-offer файлов
        foreach ($offer->ПакетПредложений->Предложения->Предложение as $item) {
            $id = (string)$item->Ид;
            if (array_key_exists($id, $res['items'])) {
                $res['items'][$id]['quantity'] = (int)$item->Количество;
                $res['items'][$id]['price'] = isset($item->Цены->Цена[0]) ? (string)$item->Цены->Цена[0]->Представление : 0;
            }
        }

        return $res;
    }

    /**
     * Формируем строку в JSON для последующей записи
     * @param $items
     * @param $cityID
     * @return boolean
     */
    protected function saveDisk($items, $cityID)
    {
        $file = self::DIR . '/json/' . date('Ymd'). '_'. $cityID . '.json';
        $json = json_encode($items);

        return file_put_contents($file, $json);

    }
}