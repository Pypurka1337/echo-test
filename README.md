# Echo-test

## Немного о проекте:

Проект представляет собой небольшой api по тестовому заданию компании Echo.
С тестовым заданием можно ознакомиться в файле [/public/docs/Тестовое_задание](https://github.com/Pypurka1337/echo-test/blob/main/public/docs/%D0%A2%D0%B5%D1%81%D1%82%D0%BE%D0%B2%D0%BE%D0%B5_%D0%B7%D0%B0%D0%B4%D0%B0%D0%BD%D0%B8%D0%B5_backend%20_echo.pdf)

## Развертка проекта

### 1. Клонировать репозиторий

```git clone https://github.com/Pypurka1337/echo-test.git ```

### 2. Создать фаил .env на основе файла .env.example

### 3. Запускаем докер

```docker-compose up -d```

### 4. Загрузить пакеты composer

```docker-compose run --rm --no-deps php-fpm composer install```

### 5. Даем доступ к категории storage

```docker-compose run --rm --no-deps webserver chmod -R 777 storage/```

### 6. Генерируем ключи шифрования

```docker-compose run --rm --no-deps php-fpm php artisan key:generate```

### 7. Сбрасываем конфигурацию

```docker-compose run --rm --no-deps php-fpm php artisan config:cache```

### 8. Включаем миграции

```docker-compose run --rm --no-deps php-fpm php artisan migrate```

### 9. Заполняем БД данными

```docker-compose run --rm --no-deps php-fpm php artisan db:seed  ```

### ГОТОВО!

## Теперь можно получить доступ к документации по адресу [http://localhost/api/documentation](http://localhost/api/documentation)

    Путь к документации: "http://localhost/docs/api.yaml"

## Выявленные Недостатки\Недоработки

- В связи с первым опытом с OpenApi 3.0 не мог реализовать полное описание API, а именно переменные query запроса. Там
  просто неассоциативный массив в неассоциативном массиве, и я не смог разобраться как это реализовать.
