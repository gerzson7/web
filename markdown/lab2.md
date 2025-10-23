# Лабораторная работа №2: Nginx + PHP-FPM, HTML-формы и JS

## Цель
Настройка Nginx + PHP-FPM в Docker; создание HTML-формы и обработка через JavaScript (без перезагрузки).

## Содержимое проекта
- `docker-compose.yml` — конфигурация Docker Compose (nginx + php:fpm)
- `nginx/default.conf` — конфигурация Nginx, обработка .php файлов через php-fpm
- `www/index.php` — phpinfo() для проверки работы PHP
- `www/form.html` — страница с формой (вариант: Заявка в библиотеку) + JS-обработка без перезагрузки, стили и анимация

## Быстрый старт (локально)
1. Убедитесь, что установлен Docker (Docker Engine / Docker Desktop)
2. Из корня проекта запустите:

```bash
docker compose up -d --build
```

3. Откройте в браузере:
- `http://localhost:8080/` — phpinfo()
- `http://localhost:8080/form.html` — форма регистрации

4. Чтобы перезагрузить конфигурацию nginx без остановки контейнеров:

```bash
docker compose exec nginx nginx -s reload
```

## Скриншоты

> Этап 1. Проверка работы PHP:
![1](<../screenshots/lab2/лаба2_проверка работы рhp.png>)

> Этап 2. Готовая форма:
![2](<../screenshots/lab2/лаба2_готовая форма.png>)
