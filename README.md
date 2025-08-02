# Organizations API

API для работы с организациями, зданиями и видами деятельности. Проект построен на Laravel 12 с использованием современных инструментов разработки и анализа кода.

## 📋 Описание проекта

Система управления организациями с возможностями:
- Управление организациями и их данными
- Привязка организаций к зданиям с геолокацией
- Иерархическая система видов деятельности
- Поиск по различным критериям (название, геолокация, виды деятельности)
- REST API с документацией Swagger
- Аутентификация через Laravel Sanctum

## 🏗️ Архитектура

### Модели данных

- **Building** - Здания с адресами и координатами
- **Organization** - Организации, привязанные к зданиям
- **Activity** - Виды деятельности (иерархическая структура)
- **OrganizationPhone** - Телефоны организаций
- **OrganizationActivity** - Связь организаций с видами деятельности (many-to-many)

### Связи между моделями

```
Building (1) ←→ (N) Organization (N) ←→ (N) Activity
                      ↓ (1)
                   (N) OrganizationPhone
```

## 🚀 Установка и настройка

### Требования

- PHP 8.3+
- Composer
- Node.js 18+
- MySQL/PostgreSQL/SQLite

### Установка

1. **Клонирование репозитория**
```bash
git clone <repository-url>
cd mkk_moon_task
```

2. **Установка зависимостей**
```bash
composer install
npm install
```

3. **Настройка окружения**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Настройка базы данных**
Отредактируйте `.env` файл с настройками БД:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=organizations_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Миграции и сиды**
```bash
php artisan migrate
php artisan db:seed
```

6. **Генерация API документации**
```bash
php artisan l5-swagger:generate
```

## 🛠️ Разработка

### Запуск проекта

**Полный стек разработки (рекомендуется):**
```bash
composer run dev
```
Эта команда запускает:
- Laravel сервер (http://localhost:8000)
- Queue worker
- Logs (Pail)
- Vite dev server

**Отдельные команды:**
```bash
# Laravel сервер
php artisan serve

# Frontend разработка
npm run dev

# Сборка для продакшена
npm run build
```

### Инструменты качества кода

**PHPStan (статический анализ):**
```bash
./vendor/bin/phpstan analyse
```

**Rector (автоматический рефакторинг):**
```bash
./vendor/bin/rector process --dry-run  # просмотр изменений
./vendor/bin/rector process            # применение изменений
```

**Laravel Pint (форматирование кода):**
```bash
./vendor/bin/pint --test  # проверка
./vendor/bin/pint         # исправление
```

**Тестирование:**
```bash
composer run test
# или
php artisan test
```

## 📚 API Документация

### Swagger UI
После запуска проекта документация доступна по адресу:
```
http://localhost:8000/api/documentation
```

### Основные эндпоинты

#### Аутентификация
Все эндпоинты (кроме `/buildings`) требуют аутентификации через Bearer token.

#### Организации

- `GET /api/organizations/{id}` - Получить организацию по ID
- `GET /api/organizations/building/{building_id}` - Организации в здании
- `GET /api/organizations/activity/{activity_id}` - Организации по виду деятельности
- `GET /api/organizations/search/name?name={query}` - Поиск по названию
- `POST /api/organizations/search/activity-tree` - Поиск по дереву видов деятельности
- `POST /api/organizations/search/geo/radius` - Поиск по радиусу
- `POST /api/organizations/search/geo/rectangle` - Поиск в прямоугольной области

#### Здания

- `GET /api/buildings` - Список всех зданий

### Примеры запросов

**Поиск по радиусу:**
```json
POST /api/organizations/search/geo/radius
{
    "latitude": 55.751244,
    "longitude": 37.618423,
    "radius": 5.0
}
```

**Поиск по дереву видов деятельности:**
```json
POST /api/organizations/search/activity-tree
{
    "activity_id": 1
}
```

## 🗄️ База данных

### Структура таблиц

- `buildings` - здания (id, address, latitude, longitude)
- `organizations` - организации (id, building_id, name)
- `activities` - виды деятельности (id, parent_id, name)
- `organization_phones` - телефоны (id, organization_id, phone)
- `organization_activities` - связи (id, organization_id, activity_id)

### Сиды

Проект включает фабрики и сиды для генерации тестовых данных:
- 10 зданий с случайными адресами и координатами
- 10 организаций
- Иерархическая структура видов деятельности
- Телефоны и связи организаций с видами деятельности

## 🔧 Конфигурация

### PHPStan
Настроен на уровне 8 с анализом:
- `app/` - основной код приложения
- `config/` - конфигурационные файлы
- `bootstrap/` - загрузочные файлы
- `database/factories/` - фабрики
- `routes/` - маршруты

### Rector
Настроен для автоматического рефакторинга с поддержкой:
- Современных возможностей PHP
- Улучшения качества кода
- Обнаружения мертвого кода

## 🚀 Деплой

### Продакшен

1. **Сборка ассетов:**
```bash
npm run build
```

2. **Оптимизация:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

3. **Миграции:**
```bash
php artisan migrate --force
```

## 📝 Лицензия

MIT License

## 🤝 Участие в разработке

1. Fork проекта
2. Создайте feature branch (`git checkout -b feature/amazing-feature`)
3. Commit изменения (`git commit -m 'Add amazing feature'`)
4. Push в branch (`git push origin feature/amazing-feature`)
5. Откройте Pull Request

### Стандарты кода

- Используйте PHPStan для статического анализа
- Запускайте Pint для форматирования
- Покрывайте новый код тестами
- Следуйте PSR-12 стандартам

## 🛡️ Безопасность

### Аутентификация
Проект использует Laravel Sanctum для API аутентификации. Для доступа к защищенным эндпоинтам необходимо:

1. Получить API токен
2. Передавать его в заголовке: `Authorization: Bearer {token}`

### Валидация данных
Все входящие данные проходят валидацию через Form Request классы:
- `OrganizationSearchRequest` - для поиска по названию
- `ActivitySearchRequest` - для поиска по видам деятельности
- `GeoSearchRequest` - для геопоиска

## 🧪 Тестирование

### Запуск тестов
```bash
# Все тесты
php artisan test

# С покрытием кода
php artisan test --coverage

# Конкретный тест
php artisan test --filter=OrganizationTest
```

### Структура тестов
- `tests/Feature/` - интеграционные тесты API
- `tests/Unit/` - юнит-тесты моделей и сервисов

## 📊 Мониторинг и логирование

### Логи
Проект использует Laravel Pail для мониторинга логов в реальном времени:
```bash
php artisan pail
```

### Метрики
- Время выполнения запросов
- Использование памяти
- Количество SQL запросов

## 🔍 Отладка

### Laravel Telescope (опционально)
Для детального мониторинга можно установить Telescope:
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### Debug режим
В `.env` файле для разработки:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## 📈 Производительность

### Кэширование
- Конфигурация: `php artisan config:cache`
- Маршруты: `php artisan route:cache`
- Представления: `php artisan view:cache`

### Оптимизация БД
- Индексы на внешние ключи
- Индексы для геопоиска
- Soft deletes для всех основных таблиц

### Пагинация
API автоматически применяет пагинацию для больших наборов данных.

## 🌍 Интернационализация

Проект поддерживает русский язык:
- Сообщения об ошибках
- Валидация
- API документация

## 📞 Поддержка

При возникновении вопросов или проблем:
1. Проверьте документацию API
2. Изучите логи приложения
3. Создайте issue в репозитории

## 🔄 Обновления

### Миграции
При обновлении проекта всегда выполняйте:
```bash
php artisan migrate
```

### Зависимости
Регулярно обновляйте зависимости:
```bash
composer update
npm update
```

## 📋 TODO

- [ ] Добавить кэширование для часто запрашиваемых данных
- [ ] Реализовать rate limiting для API
- [ ] Добавить экспорт данных в различные форматы
- [ ] Интеграция с внешними картографическими сервисами
- [ ] Добавить веб-интерфейс для управления данными
