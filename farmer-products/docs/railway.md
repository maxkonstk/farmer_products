# Деплой на Railway

Проект переведен на явный Docker-based deploy. Это убирает зависимость от Railpack autodetect и его `npm ci`/PHP startup логики.

Текущая схема:

- `app service` обслуживает HTTP;
- `worker service` обрабатывает очередь;
- `Postgres` хранит данные;
- `Volume` монтируется в `storage/app/public` для загруженных изображений;
- `cron service` опционален.

## Важно для этого репозитория

Laravel-приложение лежит не в корне git-репозитория, а в подпапке `farmer-products`.

Для каждого кодового сервиса укажи:

```text
Root Directory = /farmer-products
```

Без этого Railway будет собирать корень монорепозитория, а не само приложение.

## Что теперь отвечает за деплой

- `Dockerfile` собирает production image;
- `railway.json` это конфиг `app service`;
- `railway.worker.json` это конфиг `worker service`;
- `railway.cron.json` это конфиг `cron service`;
- `railway/pre-deploy.sh` выполняет миграции;
- `railway/start-app.sh` поднимает Apache на Railway `PORT`;
- `railway/run-worker.sh` запускает `queue:work`;
- `railway/run-cron.sh` запускает `schedule:work`.

## Почему теперь не используется Railpack

Railway официально всегда использует `Dockerfile`, если он найден в корне source directory. Также Railway позволяет задавать отдельный config-as-code файл для каждого сервиса. Это удобно для одного репозитория с `app` и `worker`, которым нужен один и тот же image, но разные start-команды.

## App service

1. Создай проект Railway.
2. Добавь `Postgres`.
3. Создай сервис из GitHub-репозитория.
4. Укажи `Root Directory = /farmer-products`.
5. Не задавай `Custom Build Command`.
6. Не задавай `Custom Start Command`.
7. Проверь, что сервис использует обычный `railway.json` из проекта.
8. Добавь Volume и смонтируй его в:

```text
/var/www/html/storage/app/public
```

9. В `Variables` перенеси значения из `.env.railway.example`.
10. Обязательно заполни:
   - `APP_KEY` из `php artisan key:generate --show`;
   - `APP_URL` публичным доменом Railway;
   - `DB_URL` как `${{Postgres.DATABASE_URL}}`.
11. В `Networking` создай public domain.

Для `app service` отдельный `Start Command` не нужен: контейнер стартует через `railway/start-app.sh`.

## Worker service

1. Создай второй сервис из того же репозитория.
2. Укажи `Root Directory = /farmer-products`.
3. В `Settings` для config-as-code укажи custom config path:

```text
/farmer-products/railway.worker.json
```

4. Не задавай `Custom Start Command`.
5. Скопируй те же переменные окружения, что и у `app service`.

Отдельный volume worker сейчас не нужен. Если позже jobs начнут читать или писать `storage/app/public`, подключи тот же volume и сюда.

## Cron service

Если понадобится always-on планировщик:

1. Создай еще один сервис из этого же репозитория.
2. Укажи `Root Directory = /farmer-products`.
3. Укажи custom config path:

```text
/farmer-products/railway.cron.json
```

Если задачи редкие, вместо отдельного always-on cron-сервиса можно использовать Railway Cron Job с командой `php artisan schedule:run`.

## Почему нужен volume

Изображения товаров и категорий пишутся в `storage/app/public`. Без volume они исчезнут после redeploy или рестарта контейнера.

Bucket Railway здесь не является прямой заменой, потому что текущее приложение рассчитывает на публичные URL через `/storage/...`.

## Проверка после деплоя

1. У app service должен открываться `/up`.
2. Должна открываться главная страница.
3. В админке должна работать загрузка изображения.
4. После тестового заказа job должна уходить из таблицы `jobs`, если поднят worker.

## Переменные и замечания

- Railway импортирует `.env*` только из корня репозитория, поэтому `.env.railway.example` внутри `farmer-products` используй как шаблон вручную.
- Миграции теперь запускаются через `railway/pre-deploy.sh` в `app service`, а не через Railpack.
- Образ сам поднимает Apache на Railway `PORT`, поэтому вручную `PORT` задавать не нужно.
