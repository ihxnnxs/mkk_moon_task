.PHONY: help setup up down build restart logs shell composer artisan migrate seed fresh

setup:
	@echo "🚀 Запуск настройки Laravel проекта..."
	@echo "⚙️ Создание .env файла..."
	@if [ ! -f .env ]; then \
		if [ -f .env.example ]; then \
			cp .env.example .env; \
		else \
			echo "APP_NAME=Laravel" > .env; \
			echo "APP_ENV=local" >> .env; \
			echo "APP_KEY=" >> .env; \
			echo "APP_DEBUG=true" >> .env; \
			echo "APP_URL=http://localhost:8080/api/documentation" >> .env; \
			echo "" >> .env; \
			echo "DB_CONNECTION=pgsql" >> .env; \
			echo "DB_HOST=db" >> .env; \
			echo "DB_PORT=5432" >> .env; \
			echo "DB_DATABASE=laravel" >> .env; \
			echo "DB_USERNAME=laravel" >> .env; \
			echo "DB_PASSWORD=laravel" >> .env; \
		fi; \
	fi
	@echo "🐳 Сборка и запуск Docker контейнеров..."
	docker-compose up -d --build
	@echo "⏳ Ожидание запуска контейнеров..."
	@sleep 15
	@echo "🔧 Обновление конфигурации базы данных..."
	@sed -i 's/DB_HOST=.*/DB_HOST=db/' .env || true
	@sed -i 's/DB_PORT=.*/DB_PORT=5432/' .env || true
	@sed -i 's/DB_DATABASE=.*/DB_DATABASE=laravel/' .env || true
	@sed -i 's/DB_USERNAME=.*/DB_USERNAME=laravel/' .env || true
	@sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=laravel/' .env || true
	@echo "📦 Установка Composer зависимостей..."
	docker-compose exec -T app composer install
	@echo "🔑 Генерация ключа приложения..."
	docker-compose exec -T app php artisan key:generate
	@echo "⏳ Ожидание базы данных..."
	@sleep 10
	@echo "🗄️ Запуск миграций базы данных..."
	docker-compose exec -T app php artisan migrate --force
	@echo "🌱 Заполнение базы данных..."
	docker-compose exec -T app php artisan db:seed --force || true
	@echo "🔧 Исправление прав доступа..."
	docker-compose exec -T app chown -R www:www /var/www/storage /var/www/bootstrap/cache || true
	@echo "🔧 Генерация документации API..."
	docker-compose exec -T app php artisan l5-swagger:generate || true
	@echo ""
	@echo "✅ Настройка завершена!"
	@echo "🌐 Откройте http://localhost:8080/api/documentation в браузере"
	@echo "📋 Доступные команды: make help"

help:
	@echo "Команды Laravel Docker:"
	@echo "  setup     - 🚀 Полная настройка проекта (запускать после клонирования)"
	@echo "  up        - Запустить контейнеры"
	@echo "  down      - Остановить контейнеры"
	@echo "  build     - Собрать контейнеры"
	@echo "  restart   - Перезапустить контейнеры"
	@echo "  logs      - Показать логи"
	@echo "  shell     - Зайти в контейнер приложения"
	@echo "  composer  - Установить зависимости"
	@echo "  migrate   - Запустить миграции"
	@echo "  seed      - Запустить заполнение БД"
	@echo "  fresh     - Свежие миграции + заполнение"

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

restart:
	docker-compose restart

logs:
	docker-compose logs -f

shell:
	docker-compose exec app bash

composer:
	docker-compose exec app composer install

migrate:
	docker-compose exec app php artisan migrate

seed:
	docker-compose exec app php artisan db:seed

fresh:
	docker-compose exec app php artisan migrate:fresh --seed

artisan:
	docker-compose exec app php artisan $(cmd)
