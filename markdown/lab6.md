# Лабораторная работа №6
## Тема: 
Изучение нереляционных баз данных (Redis, Elasticsearch, ClickHouse) и взаимодействие с ними через API с помощью GuzzleClient.

## Цель
 Закрепить навыки работы с HTTP-запросами и API-интерфейсами для взаимодействия с современными нереляционными СУБД.


## Содержимое проекта
- `docker-compose.yml` — конфигурация Docker Compose (php-fpm + redis + elasticsearch + clickhouse)
- `Dockerfile` — конфигурация PHP-FPM с расширениями и Composer
- `nginx.conf` — конфигурация Nginx, обработка php файлов через php-fpm
- `www/index.php` — главная страница с демонстрацией работы всех СУБД
- `www/ElasticExample.php` —класс для работы с Elasticsearch
- `www/ClientFactory.php` — фабрика для создания HTTP-клиентов




## Скриншоты
> Шаг 1. Реализация класса ClientFactory для создания HTTP-клиентов с базовой конфигурацией
![0](<../screenshots/lab6/1.png>) 

> Шаг 2. Конфигурация файла composer.json с подключением Guzzle HTTP Client и настройкой автозагрузки
![1](<../screenshots/lab6/2.png>)

>  Шаг 3. Основной класс для работы с Elasticsearch
![2](<../screenshots/lab6/3.png>)

>  Шаг 4. Главная страница приложения с формой добавления заявок, поиском и отображением статистики
![3](<../screenshots/lab6/4.png>)

>  Шаг 5. Настройки PHP-FPM для оптимальной работы 
![4](<../screenshots/lab6/5.png>)

> Шаг 6. Настройка многоконтейнерного приложения с nginx, PHP-FPM и Elasticsearch
![5](<../screenshots/lab6/6.png>)

> Шаг 7. Конфигурация Dockerfile с установкой PHP расширений и Composer
![6](<../screenshots/lab6/7.png>)


> Итоговая работа: Рабочее веб-приложение для управления заявками библиотеки с поиском через Elasticsearch:
![7](<../screenshots/lab6/9.png>)
