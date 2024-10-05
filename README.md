# School Management

This is a simple school management application built with Laravel.

> [!IMPORTANT]
> This project is in beta stage and is not yet ready for production use.
> This first version is still under development and may contain bugs or incomplete features.

## Installation

1. Clone the repository:

```bash
git clone https://github.com/albertlnz/school-management.git
```

2. Change directory:

```bash
cd school-management
```

3. Install dependencies:

```bash
composer install
```

4. Do the database migrations with the seeders:

```bash
php artisan migrate:fresh --seed
```

5. Start the development server:

```bash
php artisan serve
```

6. Open your browser and visit http://localhost:8000

## Usage

### API Documentation

The API documentation is available at http://localhost:8000/api/documentation.

### Database

The database is stored in the `database` directory.
