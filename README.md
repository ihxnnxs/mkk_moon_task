# Organizations API

## Запуск

```bash
git clone https://github.com/ihxnnxs/mkk_moon_task.git
cd mkk_moon_task
make setup
```

Готово! Откройте: `http://localhost:8080/api/documentation`

## API

- `GET /api/organizations/{id}` - организация
- `GET /api/organizations/building/{id}` - по зданию
- `GET /api/organizations/activity/{id}` - по деятельности
- `GET /api/organizations/search/name?name=поиск` - поиск по имени
- `POST /api/organizations/search/geo/radius` - поиск в радиусе
- `POST /api/organizations/search/geo/rectangle` - поиск в области
- `POST /api/organizations/search/activity/tree` - поиск по дереву
- `GET /api/buildings` - все здания

## Команды

```bash
make help
```
