## Парсер
В работе применен фреймворк Yii2
### Часть 1
Выполнена для работе в консоле, запускается командой yii xml, которая выводит справку по работе.
Импорт разбит на два этапа.

Команда yii import ищет файлы в указанной директории (в моем случае ./data) по заданному шаблону файлов, парсит xml, объединяет значения из import и offer для конкретного города и сохраняет сведенные данные в единый файл для конкретного города. Файл имеет формат YYYYMMDD_кодГорода.json и сохраняет его в поддиректории json.

Второй шаг - сохранение данных в БД, выполняется командой в консоле yii save, ход выполнения выводится на экран.
### Часть 2
Визуализируются строки из таблицы БД с постраничной навигацией и возможностью сортировки по столбцам. По умочанию включена обратная сортировка по полю id.
## PS
Некоторые результаты (дамп БД, скриншот приложения из части 2, ссылки на json-файлы) размещены в поддиректории results
