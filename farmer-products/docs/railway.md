# Деплой на Railway

Этот проект подготовлен под Railway в конфигурации:

- `app service` для HTTP-трафика;
- `worker service` для очереди Laravel;
- `Postgres` как основная база;
- `Volume` для `storage/app/public`, чтобы не терялись загружаемые изображения;
- `cron service` опционально, если позже появятся задачи в планировщике.

## Важно для этого репозитория

Текущий Laravel-проект лежит не в корне git-репозитория, а в подпапке `farmer-products`.

Поэтому для каждого кодового сервиса Railway нужно явно задать `Root Directory`:

```text
/farmer-products
```

Если этого не сделать, Railway попытается собирать корень монорепозитория, увидит только папку `farmer-products/` и не найдет `composer.json` в корне сборки.

## Важно про Pre-Deploy и storage

У Railway `Pre-Deploy Command` выполняется в отдельном контейнере:

- изменения файловой системы из `Pre-Deploy` не попадают в runtime-контейнер;
- volume в `Pre-Deploy` не монтируется.

Поэтому в `Pre-Deploy` для этого проекта нужно оставлять только миграции БД.

`storage:link` туда выносить нельзя: ссылка не сохранится. Для `app service` это не проблема, потому что стандартный Laravel startup у Railpack сам создает storage symlink при запуске контейнера.

По этой же причине в `.env.railway.example` включен `RAILPACK_SKIP_MIGRATIONS=true`: миграции идут через Railway `Pre-Deploy`, а не вторым проходом во время старта HTTP-сервиса.

## Почему нужен volume

Проект хранит загружаемые изображения товаров и категорий на диске `public`, а Railway использует эфемерный файловый слой. Без volume изображения, загруженные из админки, исчезнут после redeploy или рестарта.

Для текущей реализации bucket Railway не является drop-in заменой: bucket приватный, а проект ожидает обычные публичные URL картинок через `/storage/...`.

## Что уже добавлено в репозиторий

- `.env.railway.example` с базовым набором переменных;
- `composer.json` явно требует `ext-pgsql`, чтобы Railpack установил PostgreSQL extension для PHP;
- `railway/pre-deploy.sh` только для миграций;
- `railway/run-worker.sh` для `queue:work`;
- `railway/run-cron.sh` для `schedule:work`.

## App service

1. Создай проект в Railway.
2. Добавь сервис `Postgres`.
3. Создай сервис из GitHub-репозитория этого проекта.
4. В `Settings` задай `Root Directory` = `/farmer-products`.
5. В `Build` укажи `Custom Build Command`:

```sh
npm run build
```

6. Не задавай `Custom Start Command` для `app service`.

Railway сам поднимет Laravel HTTP-сервис штатным способом. Это важно: именно этот стартовый flow создает `public/storage` symlink в runtime-контейнере.

7. Добавь Volume и смонтируй его в `/app/storage/app/public`.
8. В `Variables` вставь значения из `.env.railway.example` вручную.
9. Установи:
   - `APP_KEY` из `php artisan key:generate --show`;
   - `APP_URL` в публичный домен Railway;
   - `DB_URL` как `${{Postgres.DATABASE_URL}}`.
10. В `Deploy -> Pre-Deploy Command` укажи:

```sh
chmod +x ./railway/pre-deploy.sh && ./railway/pre-deploy.sh
```

11. Сгенерируй публичный домен в `Networking`.

Только `app service` нужен публичный домен.

## Worker service

Создай второй сервис из того же репозитория и задай `Custom Start Command`:

```sh
chmod +x ./railway/run-worker.sh && ./railway/run-worker.sh
```

Переменные окружения должны совпадать с `app service`, потому что worker использует ту же БД, очередь и mail-конфиг.

Для worker тоже нужно поставить `Root Directory` = `/farmer-products`.

Отдельный volume worker сейчас не нужен: текущие queued job в проекте отправляют email и не работают с загруженными файлами. Если позже в очередь будут вынесены задачи, которые читают или пишут `storage/app/public`, подключи тот же volume и к worker.

## Cron service

Сейчас в проекте нет задач планировщика, поэтому отдельный cron service не обязателен.

Если он понадобится позже, используй `Custom Start Command`:

```sh
chmod +x ./railway/run-cron.sh && ./railway/run-cron.sh
```

Если позже появятся редкие задачи, которым достаточно запуска не чаще чем раз в 5 минут, дешевле использовать нативный Railway Cron Job и команду `php artisan schedule:run`. Если нужна обычная Laravel-модель с минутной точностью, оставляй отдельный always-on service с `schedule:work`.

## Проверка после деплоя

1. Открой `/up` и главную страницу.
2. Авторизуйся в админке и загрузи тестовое изображение товара.
3. Оформи тестовый заказ.
4. Убедись, что job исчезает из таблицы `jobs`, а письмо уходит через worker.

## Замечания

- Если хочешь совсем простой старт без отдельного worker, можно временно поставить `QUEUE_CONNECTION=sync`, но тогда уведомления перестанут идти через очередь.
- Если позже захочешь горизонтальное масштабирование и общее хранилище медиа, лучше вынести изображения в S3/R2-совместимое публичное object storage и отдельно адаптировать код проекта под это.
- Railway предлагает переменные из `.env*` только из корня репозитория. Так как приложение лежит в подпапке, `.env.railway.example` из `farmer-products` удобнее использовать как локальную шпаргалку и переносить значения в UI вручную.
- Если в логах было `could not find driver` или `PDOException` при подключении к Postgres, причина как раз в отсутствии PostgreSQL extension в PHP runtime. В текущем состоянии репозитория это уже учтено через `ext-pgsql` в `composer.json`.
