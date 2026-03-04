# WB API — Laravel Data Fetcher

Проект стягивает данные с тестового API (Wildberries-подобного) и сохраняет их в MySQL.

---

## Стек

- PHP 8.3.30
- Laravel 12.53.0
- MySQL 8.0
- Docker / Docker Compose
- Nginx

---

## Эндпоинты API

| Сущность | Метод | Путь | Параметры |
|---|---|---|---|
| Продажи | GET | `/api/sales` | `dateFrom`, `dateTo` |
| Заказы | GET | `/api/orders` | `dateFrom`, `dateTo` |
| Склады | GET | `/api/stocks` | `dateFrom` (только текущий день) |
| Доходы | GET | `/api/incomes` | `dateFrom`, `dateTo` |

**Общие параметры:** `page`, `limit` (макс. 500), `key` (токен авторизации)

**Формат дат:** `Y-m-d`

**Пример запроса:**
```
GET /api/orders?dateFrom=2026-02-01&dateTo=2026-03-01&page=1&limit=500&key=YOUR_TOKEN
```

---

## Источник данных (внешний API)

- **Хост:** `109.73.206.144:6969`
- **Ключ:** `E6kUTYrYwZq2tN4QEtyzsbEBk3ie`

---

## Структура таблиц БД

### `sales`
| Колонка | Тип |
|---|---|
| id | bigint PK |
| sale_id | varchar UNIQUE |
| date | datetime |
| product_name | varchar |
| sku | varchar |
| quantity | int |
| amount | decimal(10,2) |
| warehouse | varchar |
| created_at / updated_at | timestamp |

### `orders`
| Колонка | Тип |
|---|---|
| id | bigint PK |
| order_id | varchar UNIQUE |
| sku | varchar |
| order_date | datetime |
| customer_name | varchar |
| total_amount | decimal(10,2) |
| status | varchar |
| created_at / updated_at | timestamp |

### `stocks`
| Колонка | Тип |
|---|---|
| id | bigint PK |
| stock_id | varchar |
| date | datetime |
| warehouse | varchar |
| product_name | varchar |
| sku | varchar |
| quantity | int |
| created_at / updated_at | timestamp |

### `incomes`
| Колонка | Тип |
|---|---|
| id | bigint PK |
| income_id | varchar |
| date | datetime |
| amount | decimal(10,2) |
| source | varchar |
| created_at / updated_at | timestamp |

---

## Доступы к БД (продакшн)

| Параметр | Значение |
|---|---|
| Host | `wb-mysql-wb-api-project.i.aivencloud.com` |
| Port | `16031` |
| Database | `defaultdb` |
| Username | `avnadmin` |
| Password | `AVNS_1bWxuvVZwMwMCtSjNKT` |
| SSL | Required (ca.pem) |


---

## Локальный запуск

### 1. Клонировать репозиторий

```bash
git clone https://github.com/YOUR_USERNAME/wb-api.git
cd wb-api
```

### 2. Создать `.env`

```bash
cp .env.example .env
```

Заполнить переменные:

```env
APP_KEY=          # сгенерировать: php artisan key:generate
DB_HOST=db
DB_PORT=3306
DB_DATABASE=wb_api
DB_USERNAME=root
DB_PASSWORD=secret

API_HOST=109.73.206.144:6969
API_TOKEN=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
```

### 3. Запустить Docker

```bash
docker compose up -d
```

### 4. Применить миграции

```bash
docker exec -it wb_app php artisan migrate
```

### 5. Запустить импорт данных

```bash
docker exec -it wb_app php artisan import:all
```

---

## Контейнеры

| Имя | Образ | Порт |
|---|---|---|
| wb_nginx | nginx:stable-alpine | 8000 → 80 |
| wb_app | wb-api-app (PHP-FPM) | 9000 |
| wb_db | mysql:8.0 | 3306 |

---

## Архитектура кода

```
app/
├── Filters/
│   ├── FilterInterface.php     — контракт
│   ├── BaseFilter.php          — пагинация, лимит, appends
│   ├── SaleFilter.php
│   ├── OrderFilter.php
│   ├── StockFilter.php
│   └── IncomeFilter.php
├── Http/
│   ├── Controllers/Api/        — тонкие контроллеры
│   ├── Middleware/
│   │   └── CheckApiKey.php     — авторизация по токену
│   ├── Requests/               — валидация входных параметров
│   └── Resources/              — форматирование JSON-ответов
└── Models/
    ├── Sale.php
    ├── Order.php
    ├── Stock.php
    └── Income.php
```
