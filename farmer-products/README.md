# Фермерская лавка

Учебный, но уже приближенный к production веб-проект на Laravel по теме «Интернет-магазин фермерских продуктов».

Проект реализует:
- каталог и карточки товаров;
- корзину и оформление заказа;
- историю заказов в личном кабинете;
- подтверждение email для защищенных разделов;
- административную панель;
- загрузку изображений товаров и категорий;
- очередь почтовых уведомлений;
- базовую подготовку к деплою и эксплуатации.

## Технологии

- Laravel 13
- Blade
- Eloquent ORM
- SQLite / MySQL / PostgreSQL
- Vite
- Laravel Breeze
- очередь Laravel Queue
- mail notifications

## Архитектура

### Основные слои

- `app/Models`
  `User`, `Category`, `Product`, `Order`, `OrderItem`
- `app/Models/Concerns`
  trait `HasUniqueSlug` для устойчивой генерации slug
- `app/Http/Controllers`
  пользовательские сценарии магазина и кабинета
- `app/Http/Controllers/Admin`
  административный CRUD
- `app/Http/Requests`
  отдельные правила валидации для форм
- `app/Services`
  `CartService` и `OrderService`
- `app/Notifications`
  почтовое уведомление `OrderPlacedNotification`
- `config/shop.php`
  бизнес-настройки магазина
- `database/migrations`
  схема БД и production-индексы
- `resources/views`
  Blade-шаблоны сайта, кабинета, auth и admin

### Бизнес-логика

- корзина хранится в сессии;
- заказ оформляется транзакционно с проверкой остатков;
- при создании заказа формируется уникальный `order_number`;
- после оформления создается уведомление покупателю через очередь;
- slug категорий и товаров формируются устойчиво и не конфликтуют;
- навигационные категории кэшируются.

## Структура базы данных

### `users`

- `id`
- `name`
- `email`
- `phone`
- `email_verified_at`
- `password`
- `is_admin`

### `categories`

- `id`
- `name`
- `slug`
- `description`
- `image`

### `products`

- `id`
- `category_id`
- `name`
- `slug`
- `description`
- `price`
- `image`
- `weight`
- `stock`
- `is_active`
- `is_featured`

### `orders`

- `id`
- `order_number`
- `user_id`
- `customer_name`
- `phone`
- `email`
- `address`
- `comment`
- `total_price`
- `status`

### `order_items`

- `id`
- `order_id`
- `product_id`
- `product_name`
- `quantity`
- `price`

## Роли и доступ

### Гость

- просмотр каталога;
- работа с корзиной;
- оформление заказа;
- регистрация и авторизация.

### Пользователь

- все возможности гостя;
- доступ к личному кабинету после подтверждения email;
- просмотр истории заказов;
- доступ к истории заказов только после подтверждения email.

### Администратор

- все возможности пользователя;
- доступ в админ-панель только после подтверждения email;
- управление категориями;
- управление товарами;
- просмотр и обработка заказов.

## Реализованный функционал

### Пользовательская часть

- главная страница с баннером и популярными товарами;
- каталог с поиском и сортировкой;
- фильтрация по категориям;
- карточка товара;
- корзина с обновлением количества;
- защита от покупки недоступного товара;
- checkout с серверной валидацией;
- сохранение заказа и позиций заказа;
- страница успешного оформления;
- история заказов;
- страницы «О нас» и «Контакты».

### Административная часть

- панель обзора магазина;
- CRUD категорий;
- CRUD товаров;
- ручная загрузка изображений через `storage/app/public`;
- просмотр заказов;
- просмотр состава заказа;
- изменение статуса заказа.

### Production-улучшения

- уникальные номера заказов;
- устойчивые slug с защитой от конфликтов;
- индексы для каталога и заказов;
- подтверждение email для защищенных разделов;
- rate limiting для корзины и checkout;
- очередь уведомлений при оформлении заказа;
- шаблон production `.env`;
- команды деплоя через Composer scripts;
- кэширование навигационных категорий.

## Тестовые данные

Сидеры создают:

- 10 категорий;
- 55 товаров;
- 2 тестовых пользователя;
- 2 тестовых заказа.

Учетные записи:

- администратор: `admin@farmer-shop.test` / `password`
- пользователь: `buyer@farmer-shop.test` / `password`

## Локальный запуск

### 1. Установка

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### 2. Настройка базы данных

Быстрый локальный старт возможен на SQLite:

```bash
touch database/database.sqlite
```

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

При необходимости можно переключиться на MySQL или PostgreSQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmer_products
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Миграции и сиды

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 4. Сборка и запуск

```bash
npm run build
php artisan serve
```

Для разработки:

```bash
composer run dev
```

## Production-деплой

### 1. Подготовить окружение

- скопировать `.env.production.example` в `.env`;
- заполнить `APP_KEY`, `APP_URL`, параметры MySQL/PostgreSQL и SMTP;
- установить `APP_DEBUG=false`;
- настроить HTTPS и веб-сервер.

### 2. Выполнить деплой-команды

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan key:generate --force
composer run deploy
```

### 3. Запустить очередь

Так как уведомления о заказе отправляются через очередь, в production нужен worker:

```bash
composer run worker
```

## Railway

Railway-конфиг и пошаговая инструкция лежат в [docs/railway.md](docs/railway.md).

Коротко по этому репозиторию:

- кодовое приложение находится не в корне git-репозитория, а в подпапке `farmer-products`;
- для `app` и `worker` сервисов на Railway нужно указать `Root Directory = /farmer-products`;
- из-за monorepo-структуры нужно явно задать `Config as Code Path`:
  - `app`: `/farmer-products/railway.json`
  - `worker`: `/farmer-products/railway.worker.json`
- для первого деплоя подготовлен Docker-based runtime с `pre-deploy` миграциями, healthcheck `/up` и отдельным queue worker.

## Docker

Для нейтральной контейнерной сборки можно использовать `Dockerfile` из корня проекта:

```bash
docker build -t farmer-products .
docker run --rm -p 8080:8080 --env-file .env farmer-products
```

Для очереди в контейнерном окружении поднимай отдельный worker-процесс или второй контейнер с командой:

```bash
php artisan queue:work --tries=3 --sleep=1 --timeout=90
```

Для боевого сервера без контейнеров лучше запускать worker через `supervisor` или `systemd`.

### 4. Что должно быть включено в production

- `php artisan storage:link`
- `config:cache`
- `route:cache`
- `view:cache`
- очередь `queue:work`
- резервное копирование базы
- ротация логов

## Основные маршруты

### Публичные

- `/`
- `/catalog`
- `/categories/{category}`
- `/products/{product}`
- `/cart`
- `/checkout`
- `/about`
- `/contacts`

### Пользовательские

- `/dashboard`
- `/profile`
- `/account/orders`

### Административные

- `/admin`
- `/admin/categories`
- `/admin/products`
- `/admin/orders`

## Проверка проекта

Проверки, выполненные после production-доработки:

```bash
php artisan migrate:fresh --seed
php artisan test
php artisan route:list --except-vendor
npm run build
```
