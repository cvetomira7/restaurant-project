# 🍽️ Restaurant Project (Dockerized)

Уеб приложение за ресторант с PHP и MySQL, контейнеризирано с Docker и стартирано с помощта на Docker Compose.

## 🚀 Стартиране на проекта

1. Клонирай или свали проекта:
```bash
git clone https://github.com/yourusername/restaurant-project.git
cd restaurant-project
```

2. Стартирай контейнерите:
```bash
docker-compose up --build
```

3. Отвори в браузър:
[http://localhost:8080](http://localhost:8080)

## 📁 Структура на проекта

- `app/` – PHP файлове на сайта
- `db/init.sql` – SQL за създаване на таблици
- `Dockerfile` – конфигурация на уеб сървъра
- `docker-compose.yml` – описание на всички услуги

## 🧩 Компоненти

- `web` – PHP уеб сървър с Apache
- `db` – MySQL база данни с автоматично създаване на схема от `init.sql`

## 🔗 Комуникация между услуги

PHP се свързва с базата данни чрез:
```php
mysqli_connect("db", "user", "userpass", "restaurant")
```
Името `db` идва от docker-compose като име на услугата.

## 📤 Публикуване

1. Изгради и качи Docker образ:
```bash
docker build -t yourdockerhubuser/restaurant-web .
docker push yourdockerhubuser/restaurant-web
```
