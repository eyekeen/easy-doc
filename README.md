
# Laravel + Vue 3 + Inertia + SQLite Project

This project is a Laravel application integrated with Vue 3, Inertia.js, and SQLite. It includes functionality for generating OpenSSL keys and linking storage. Follow the steps below to set up and run the project locally.

---

## Prerequisites

Ensure you have the following installed on your system:
- PHP >= 8.1
- Composer
- Node.js >= 16.x
- npm or Yarn
- SQLite
- OpenSSL (for key generation)

---

## Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/eyekeen/easy-doc.git
cd easy-doc
```

### 2. Install Dependencies
#### Backend (Laravel)
```bash
composer install
```

#### Frontend (Vue)
```bash
npm install
```

### 3. Environment Configuration
1. Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Update the `.env` file with necessary configurations:
     ```bash
     php artisan key:generate
     ```

### 4. Generate OpenSSL Keys
Run the following command to generate OpenSSL keys in the `storage/app/private` directory:
```bash
openssl genpkey -algorithm RSA -out storage/app/private/private.key
openssl rsa -in storage/app/private/private.key -pubout -out storage/app/private/public.key
```

### 5. Link Storage
Create a symbolic link to the storage directory:
```bash
php artisan storage:link
```

### 6. Run Migrations and Seeders
Run the migrations to set up the database schema and seed initial data:
```bash
php artisan migrate --seed
```

### 7. Build Frontend Assets
Compile the frontend assets using Vite:
```bash
npm run build
```
For development, you can use:
```bash
npm run dev
```

### 8. Start the Development Server
Run the Laravel development server:
```bash
php artisan serve
```

---

## Accessing the Application

After starting the server, you can access the application at:
```
http://localhost:8000
```

---

## Notes

- **Storage:** Ensure the `storage/` directory and its subdirectories are writable.
- **Keys:** Keep the private key (`private.key`) secure and do not share it.
- **Database:** If you want to reset the database, you can clear and re-seed it:
  ```bash
  php artisan migrate:fresh --seed
  ```

---


