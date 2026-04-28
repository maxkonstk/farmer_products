# Operations Runbook

Короткий runbook для проверки storefront после деплоя и во время эксплуатации.

## Health endpoints

- `GET /up`
  Базовый Laravel liveness endpoint.
- `GET /health`
  JSON liveness для внешних проверок.
- `GET /ready`
  JSON readiness: `APP_KEY`, база, кэш, assets, storage и queue.

`/ready` возвращает:

- `200`, если приложение готово или есть только warning;
- `503`, если хотя бы один критичный check упал.

## Smoke-check

После деплоя можно выполнить:

```bash
composer run smoke-check
```

Или получить JSON-отчёт:

```bash
php artisan app:smoke-check --json
```

Команда валится с `exit code 1`, если readiness не пройден.

## Post-deploy checklist

1. `composer run smoke-check`
2. `GET /health` -> `200`
3. `GET /ready` -> `200`
4. Открыть главную, каталог и карточку товара
5. Проверить login и admin dashboard
6. Убедиться, что `public/storage` доступен и изображения загружаются
7. Проверить worker и очередь:
   - `jobs` не зависают;
   - `failed_jobs` не растёт;
8. Проверить consent banner и legal pages:
   - `/privacy`
   - `/cookies`
   - `/terms`

## Admin dashboard

В `admin` overview теперь есть два полезных блока:

- `Analytics sink`
- `Health и readiness`

Там видно:

- подключён ли `GA4`/`GTM`;
- включён ли web vitals;
- состояние базы;
- состояние кэша;
- наличие frontend assets;
- readiness публичного storage;
- pending/failed jobs для database queue.
