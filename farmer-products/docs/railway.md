# Деплой на Railway

Этот проект подготовлен под Railway через `Dockerfile`, отдельный web service и отдельный worker service.

## Что уже настроено в коде

- `Dockerfile` собирает production image с PHP 8.3, Apache и фронтенд-сборкой;
- `railway.json` задает app deploy: Dockerfile builder, миграции перед деплоем и healthcheck `/up`;
- `railway.worker.json` задает worker deploy с `queue:work`;
- `railway/pre-deploy.sh` выполняет миграции с ретраями;
- `railway/start-app.sh` поднимает Apache на Railway `PORT`;
- `railway/prepare-runtime.sh` подготавливает `storage`, права и symlink;
- `.env.railway.example` содержит безопасный production-шаблон под Railway.

## Важная особенность репозитория

Laravel-приложение лежит не в корне git-репозитория, а в подпапке `farmer-products`.

Поэтому для каждого кодового сервиса на Railway укажи:

```text
Root Directory = /farmer-products
```

И отдельно укажи путь к config-as-code файлу:

```text
App service:    /farmer-products/railway.json
Worker service: /farmer-products/railway.worker.json
```

Без этих двух настроек Railway будет либо собирать не ту директорию, либо проигнорирует нужный `railway.json`.

## Схема деплоя

Нужно создать:

1. `Postgres`
2. `app service`
3. `worker service`
4. `Volume` для загруженных изображений

Отдельный cron service сейчас не нужен: в проекте нет задач на Laravel Scheduler.
Если позже появятся scheduler-задачи, в репозитории уже лежит optional config:

```text
/farmer-products/railway.cron.json
```

## 1. Создай проект и Postgres

1. Создай новый Railway project.
2. Добавь `PostgreSQL`.
3. Дождись, пока сервис БД станет `Active`.

## 2. Подними app service

1. Добавь новый service из GitHub-репозитория.
2. В `Settings` укажи:

```text
Root Directory = /farmer-products
Config as Code Path = /farmer-products/railway.json
```

3. Не задавай `Custom Build Command`.
4. Не задавай `Custom Start Command`.
5. В `Networking` создай public domain.
6. После создания домена сделай `Redeploy` app service один раз, чтобы `APP_URL=https://${RAILWAY_PUBLIC_DOMAIN}` точно оказался в runtime-переменных.

## 3. Подключи volume

Создай Volume и подключи его только к `app service` с mount path:

```text
/var/www/html/storage/app/public
```

Это нужно для изображений, которые загружаются из админки. Без volume файлы исчезнут после redeploy или рестарта контейнера.

## 4. Заполни переменные app service

Проще всего взять значения из `.env.railway.example`.

Обязательные моменты:

1. Сгенерируй ключ локально:

```bash
php artisan key:generate --show
```

2. Вставь его в `APP_KEY`.
3. Убедись, что для базы используется:

```text
DATABASE_URL=${{Postgres.DATABASE_URL}}
DB_CONNECTION=pgsql
```

4. Если на первом деплое хочешь сразу получить демо-каталог, демо-пользователей и тестовые заказы, временно выставь:

```text
SEED_DEMO_DATA=true
```

После первого успешного деплоя это значение лучше вернуть в `false`.

5. Если пока нет SMTP, оставь `MAIL_MAILER=log`. Тогда регистрация и уведомления не сломают приложение, а письма будут попадать в логи.

## 5. Подними worker service

1. Добавь второй service из того же GitHub-репозитория.
2. В `Settings` укажи:

```text
Root Directory = /farmer-products
Config as Code Path = /farmer-products/railway.worker.json
```

3. `Custom Start Command` не задавай.
4. Скопируй в worker те же переменные окружения, что и в app service.

Отдельный volume worker сейчас не нужен: очередь не пишет пользовательские файлы в `storage/app/public`.

## 6. Что произойдет на деплое

Для `app service`:

- Railway соберет image по `Dockerfile`;
- `pre-deploy` выполнит `php artisan migrate --force` с ретраями;
- если `SEED_DEMO_DATA=true`, после миграций выполнится `php artisan db:seed --force`;
- контейнер стартует через `railway/start-app.sh`;
- Apache автоматически начнет слушать Railway `PORT`;
- healthcheck будет идти в `/up`.

Для `worker service`:

- используется тот же image;
- стартовая команда будет `./railway/run-worker.sh`;
- очередь будет работать через `php artisan queue:work`.

## 7. Проверка после первого деплоя

Проверь по порядку:

1. `https://<your-domain>/up` отдает `200`.
2. Открывается главная страница.
3. В каталоге есть товары.
4. Админ может войти под:

```text
admin@farmer-shop.test / password
```

Тестовые пользователи в сидере уже помечаются как `verified`, поэтому доступ в защищенные разделы будет работать.

5. Загрузка изображения из админки сохраняется после redeploy.
6. После тестового заказа таблица `jobs` очищается worker-ом, если он поднят.

## Минимальный набор переменных

```env
APP_NAME="Фермерская лавка"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}

LOG_CHANNEL=stderr
LOG_STDERR_FORMATTER=\Monolog\Formatter\JsonFormatter
LOG_LEVEL=info

DB_CONNECTION=pgsql
DATABASE_URL=${{Postgres.DATABASE_URL}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

MAIL_MAILER=log

MIGRATION_ATTEMPTS=30
MIGRATION_DELAY_SECONDS=3
SEED_DEMO_DATA=false

QUEUE_WORKER_QUEUE=default
QUEUE_WORKER_TRIES=3
QUEUE_WORKER_SLEEP=1
QUEUE_WORKER_TIMEOUT=90
```

## Что делать, если деплой не поднялся

- `APP_KEY` пустой: pre-deploy завершится с ошибкой, заполни `APP_KEY` и redeploy.
- `DATABASE_URL` не привязан к Postgres: миграции не смогут подключиться к БД.
- не указан `Root Directory`: Railway будет собирать не `farmer-products`, а корень монорепозитория.
- не указан `Config as Code Path`: Railway не применит `railway.json` из подпапки.
- не создан Volume: загруженные изображения будут пропадать после redeploy.

## Команда для ручной проверки в Railway shell

Если после деплоя нужно быстро проверить состояние приложения, в shell app service можно выполнить:

```bash
php artisan about
php artisan migrate:status
php artisan queue:monitor default --max=100
```
