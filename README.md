# Проект на Laravel + Vue + Inertia

## Описание проекта

Этот проект разработан с использованием Laravel в качестве бэкенда, Vue.js в качестве фронтенда и Inertia.js для интеграции между ними.  
Проект включает:
- Сидеры для заполнения базы данных данными.
- Использование локальной файловой системы для хранения файлов через `Storage`.

---

## Установка

### Требования
- PHP >= 8.1
- Composer
- Node.js >= 16.0
- npm или Yarn
- MySQL или другая поддерживаемая база данных
- Laravel >= 10

### Шаги установки

1. Склонируйте репозиторий:
   ```bash
   git clone https://github.com/eyekeen/easy-doc.git
   cd easy-doc
   ```

2. Установите зависимости PHP:
   ```bash
   composer install
   ```

3. Установите зависимости JavaScript:
   ```bash
   npm install
   # или
   yarn install
   ```

4. Создайте файл `.env` и настройте параметры подключения к базе данных:
   ```bash
   cp .env.example .env
   ```

5. Сгенерируйте ключ приложения:
   ```bash
   php artisan key:generate
   ```

6. Настройте параметры файловой системы:
   В файле `.env` установите:
   ```env
   FILESYSTEM_DRIVER=local
   ```

7. Запустите миграции и сидеры:
   ```bash
   php artisan migrate --seed
   ```

---

## Сидеры

Сидеры подключены через `DatabaseSeeder`. Они автоматически заполняют базу данных тестовыми данными.  
Если вы хотите повторно запустить сидеры, выполните команду:
```bash
php artisan db:seed
```

---

## Хранение файлов

Для хранения файлов используется локальная файловая система. Файлы сохраняются в папке `storage/app`.

Чтобы опубликовать публичные ссылки для файлов, выполните:
```bash
php artisan storage:link
```

Теперь файлы, сохраненные в `Storage`, будут доступны через URL `/storage`.

---

## Команды разработки

### Запуск локального сервера
```bash
php artisan serve
```

### Сборка ассетов
Для разработки:
```bash
npm run dev
```
Для продакшена:
```bash
npm run build
```

---

## Полезные ссылки

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Documentation](https://vuejs.org/guide/introduction.html)
- [Inertia.js Documentation](https://inertiajs.com/)

---
