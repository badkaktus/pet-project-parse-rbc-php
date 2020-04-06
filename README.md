# Pet-project RBC parse

1. Поднять контейнеры `docker-compose up`
2. Когда скачаются контейнеры, выполнить `docker-compose start`
3. Запустить миграцию для создания таблицы `docker-compose exec php ../bin/migration migrations:execute 20200330203917`
4. Спарсить новости выполнив `docker-compose exec php ../bin/console parse:latest-news`
5. Прописать в hosts `127.0.0.1 parserbc.local`
6. Перейти на [parserbc.local](http://parserbc.local:8080)