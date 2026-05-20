# Flower Shop

Інтернет-магазин квітів, розроблений на чистому PHP 8 без фреймворків з використанням патерну **MVC**.

---

## Технології

- **PHP 8** — строга типізація, стрілкові функції, named arguments
- **MySQL** + **PDO** — підготовлені запити, транзакції, JOIN-и
- **Патерн MVC** — власна реалізація без фреймворку
- **AJAX / JSON** — асинхронний пошук і кошик через `fetch()`
- **Буферизація сторінок** — кеш залежно від HTTP статус-коду
- **Vanilla JS** — без jQuery і npm

---

## Встановлення

### 1. Клонувати репозиторій

```bash
git clone https://github.com/your-username/flower-shop-mvc.git
cd flower-shop-mvc
```

### 2. Налаштувати базу даних

Створити базу даних і виконати SQL-дамп:

```bash
mysql -u root -p flower_shop < database.sql
```

### 3. Налаштувати підключення до БД

Скопіювати приклад конфігурації і заповнити своїми даними:

```bash
cp config/database.example.php config/database.php
```

```php
// config/database.php
return [
    'host'     => 'localhost',
    'dbname'   => 'flower_shop',
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
];
```

### 4. Налаштувати BASE_URL

У файлі `public/index.php` змінити:

```php
define('BASE_URL', 'http://localhost'); // або ваш домен
```

### 5. Налаштувати Apache / Virtual Host

```apache
<VirtualHost *:80>
    ServerName coursework.local
    DocumentRoot /path/to/flower-shop-mvc/public

    <Directory /path/to/flower-shop-mvc/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Або просто запустити з папки `public/` як кореневої директорії.

### 6. Права на папки

```bash
chmod -R 775 storage/
chmod -R 775 public/assets/img/uploads/
```

---

## Тестовий обліковий запис адміністратора

| Поле | Значення |
|------|----------|
| Email | `admin@flower.local` |
| Пароль | `admin123` |

> Після входу у правому верхньому куті з'явиться посилання **«Адмін-панель»**

---

## Модулі

| Модуль | Опис |
|--------|------|
| **Головна** | Вітальна сторінка зі списком категорій і новинок |
| **Каталог** | Список товарів з фільтрами по категоріях і ціні, пагінація |
| **Кошик** | Додавання/оновлення/видалення товарів через AJAX |
| **Оформлення** | Форма доставки (кур'єр / самовивіз), підтвердження замовлення |
| **Новини** | Список статей з фільтром по місяцях, пагінація |
| **Відгуки** | Відгуки на товари з рейтингом, відповідь адміна |
| **Авторизація** | Реєстрація, вхід, профіль, зміна пароля |
| **Адмін-панель** | CRUD для товарів, новин, відгуків, замовлень, користувачів |

---

## Маршрути

### Публічні

| Метод | URL | Опис |
|-------|-----|------|
| GET | `/` | Головна сторінка |
| GET | `/about` | Про нас |
| GET | `/catalog` | Каталог товарів |
| GET | `/catalog/search?q=...` | AJAX-пошук (повертає JSON) |
| GET | `/catalog/{slug}` | Сторінка товару |
| GET | `/cart` | Кошик |
| GET | `/news` | Список новин |
| GET | `/news/{slug}` | Стаття |
| GET | `/checkout` | Форма оформлення |
| GET | `/checkout/success` | Підтвердження замовлення |

### Авторизація

| Метод | URL | Опис |
|-------|-----|------|
| GET/POST | `/login` | Форма входу |
| GET/POST | `/register` | Форма реєстрації |
| GET | `/logout` | Вихід |
| GET/POST | `/profile` | Профіль користувача |

### Кошик (AJAX)

| Метод | URL | Опис |
|-------|-----|------|
| POST | `/cart/add` | Додати товар |
| POST | `/cart/update` | Змінити кількість |
| POST | `/cart/remove` | Видалити позицію |
| POST | `/cart/clear` | Очистити кошик |
| GET | `/cart/count` | Кількість товарів (JSON) |

### Адмін-панель (потрібна роль `admin`)

| Метод | URL | Опис |
|-------|-----|------|
| GET | `/admin` | Дашборд |
| GET/POST | `/admin/products` | Управління товарами |
| GET/POST | `/admin/news` | Управління новинами |
| GET/POST | `/admin/reviews` | Управління відгуками |
| GET/POST | `/admin/orders` | Управління замовленнями |
| GET/POST | `/admin/users` | Управління користувачами |

---

## Архітектура проєкту

```
flower-shop-mvc/
├── app/
│   ├── Controllers/        # Контролери (логіка запитів)
│   ├── Models/             # Моделі (робота з БД)
│   ├── Repositories/       # Репозиторії (складні запити)
│   └── Views/              # Шаблони HTML
│       └── layouts/        # Базові layout-и (main, admin)
├── config/
│   ├── routes.php          # Всі маршрути
│   ├── config.php          # Константи
│   └── database.example.php
├── core/                   # Ядро фреймворку
│   ├── Router.php          # Маршрутизатор
│   ├── Controller.php      # Базовий контролер
│   ├── Model.php           # Базова модель
│   ├── Database.php        # Singleton PDO
│   ├── Buffer.php          # Буферизація кешу
│   ├── Middleware.php      # Захист маршрутів
│   ├── Request.php         # Обгортка над запитом
│   └── Response.php        # HTTP відповіді
├── public/
│   ├── index.php           # Єдина точка входу
│   ├── .htaccess           # Перенаправлення на index.php
│   └── assets/
│       ├── css/style.css
│       ├── js/app.js
│       └── img/uploads/
└── storage/
    ├── cache/              # Файловий кеш сторінок
    └── logs/               # Логи помилок
```

---

## Ключові технічні рішення

### Буферизація залежно від статус-коду

Сторінки кешуються лише при статусі `200 OK`. Сторінки з помилками (`404`, `500`) не кешуються. Після зміни товару кеш автоматично інвалідується через `Buffer::clearCache()`.

```php
Buffer::end(); // зберігає кеш тільки якщо Response::getStatus() === 200
```

### Асинхронний обмін даними

Пошук товарів і всі операції з кошиком виконуються через `fetch()` без перезавантаження сторінки. Сервер повертає `application/json`.

### Singleton Database

Одне PDO-з'єднання на весь запит — `Database::getInstance()` завжди повертає той самий обʼєкт.

### Захист маршрутів

```php
Middleware::auth();  // тільки залогінені
Middleware::admin(); // тільки адміністратори
Middleware::csrf();  // захист форм від підробки
```

---

## Вимоги

- PHP **8.0+**
- MySQL **5.7+** або MariaDB **10.4+**
- Apache з увімкненим `mod_rewrite`
- Розширення PHP: `pdo`, `pdo_mysql`, `mbstring`, `json`
