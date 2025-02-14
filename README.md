# Laravel + Docker + Elastic Search + Kibana

## Структура
- `src/` – весь код Laravel (app, config, public, routes, vendor и т.д.)
- `nginx/default.conf` – конфигурация Nginx
- `Dockerfile`, `docker-compose.yml` – запуск окружения
- `prometheus/` – пример конфиг Prometheus (опционально)
- `README.md` – эта инструкция

## Шаги для запуска

1. Клонируйте репозиторий:
   ```bash
   git clone https://github.com/orhan17/el-search-poison.git
   cd el-search-poison
   ```

2. Скопируйте `.env.example` в `.env` (внутри папки `src/`):
   ```bash
   cp src/.env.example src/.env
   cp .env.example .env
   ```

3. Убедитесь, что в `src/.env` прописаны корректные настройки:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=laravel
   DB_PASSWORD=laravel
   ```

4. Запустите контейнеры:
   ```bash
   docker compose up --build
   ```

5. Зайдите в контейнер PHP-FPM и установите Laravel-зависимости:
   ```bash
   docker compose exec app bash
   composer install
   php artisan key:generate
   php artisan migrate --seed
   php artisan es:reindex-products
   exit
   ```

6. Проверьте в браузере:
   - [http://localhost:8000](http://localhost:8000) – главная страница Laravel
   - [http://localhost:8081](http://localhost:8081) – phpMyAdmin (логин: `laravel`, пароль: `laravel`, если не меняли)
   - [http://localhost:9090](http://localhost:9090) – Prometheus
   - [http://localhost:3000](http://localhost:3000) – Grafana
   - http://localhost:8000/api/search?q=%D0%91%D1%80%D0%BE%D1%88%D1%8C проверка индекса
