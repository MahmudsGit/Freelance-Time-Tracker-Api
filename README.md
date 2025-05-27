# Freelance Time Tracker API

A robust API built with **Laravel** to help freelancers log and manage their work time across clients and projects.

---

## Getting Started

### Prerequisites

To set up and run the Freelance Time Tracker API, ensure you have the following installed:

- **PHP**: 8.1 or higher
- **Composer**: Dependency manager for PHP
- **MySQL/PostgreSQL**: Database server

### Tech Stack

- **PHP**: 8.1

- Laravel 12

- Sanctum

- Eloquent ORM

- Factories & Seeders

- MySQL/PostgreSQL

- laravel-dompdf (for exort)

- schedule (for schedule mail)

- PHPUnit (for tests)

### Installation

Follow these steps to get the API up and running:

1. **Clone the Repository**

   ```bash
   git clone https://github.com/MahmudsGit/Freelance-Time-Tracker-Api.git
   cd Freelance-Time-Tracker-Api
   ```

2. **Install Dependencies**

   ```bash
   composer install
   ```

3. **Copy .env and Generate App Key**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure .env**

   Update the `.env` file with your database and mail settings. Example:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=freelance_time_tracker
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Run Migrations and Seeders**

   ```bash
   php artisan migrate --seed
   ```

6. **Run the Server**

   ```bash
   php artisan serve
   ```

   The API will be available at `http://localhost:8000`.

## API Authentication

The API uses **Laravel Sanctum** for token-based authentication.

1. **Register or Login** to obtain an API token.
2. Include the token in the headers for protected routes:

   ```makefile
   Authorization: Bearer <token>
   ```

## Schedule Run

created daily shedule if hours is 8+. to check this run:

   ```bash
   php artisan schedule:run
   ```

## How to Test

### Automated Testing

Run the test suite to verify the API functionality:

```bash
php artisan test
```

To run specific tests:

```bash
php artisan test --filter=CompanyTest
```

> **Note**: test environment is configured with base database.

### Manual Testing with Postman /API Documentation

1. Import the provided **Postman collection** from git root directory.
2. Start the Laravel development server:

   ```bash
   php artisan serve
   ```

3. Use the authentication endpoints (`/api/register` or `/api/login`) to obtain a token.
4. Test protected routes for clients, projects, and time log operations using the obtained token.
5. api documentation by postman:

      ```bash
      https://documenter.getpostman.com/view/21749152/2sB2qdgfZ5
      ```

## Database Structure

The API uses the following database schema to manage users, clients, projects, and time logs.

### Users

| Column       | Type     | Description                     |
|--------------|----------|---------------------------------|
| `id`         | bigint   | Primary Key                    |
| `name`       | string   | Full name                      |
| `email`      | string   | Unique email                   |
| `password`   | string   | Hashed password                |
| `timestamps` | datetime | Created and updated dates      |

### Clients

| Column            | Type     | Description                     |
|-------------------|----------|---------------------------------|
| `id`              | bigint   | Primary Key                    |
| `user_id`         | bigint   | Foreign key to `users`         |
| `name`            | string   | Client name                    |
| `email`           | string   | Client email                   |
| `contact_person`  | string   | Contact name                   |
| `timestamps`      | datetime | Created and updated dates      |

### Projects

| Column        | Type     | Description                     |
|---------------|----------|---------------------------------|
| `id`          | bigint   | Primary Key                    |
| `client_id`   | bigint   | Foreign key to `clients`       |
| `title`       | string   | Project title                  |
| `description` | text     | Project description            |
| `status`      | enum     | `active`, `completed`, etc.    |
| `deadline`    | date     | Project deadline               |
| `timestamps`  | datetime | Created and updated dates      |

### Time Logs

| Column        | Type     | Description                        |
|---------------|----------|------------------------------------|
| `id`          | bigint   | Primary Key                       |
| `project_id`  | bigint   | Foreign key to `projects`         |
| `start_time`  | datetime | Log start time                    |
| `end_time`    | datetime | Log end time                      |
| `description` | text     | Work description                  |
| `hours`       | float    | Auto-calculated or manual entry   |
| `tags`        | string   | Billable/Non-billable (optional)  |
| `timestamps`  | datetime | Created and updated dates         |

## Additional Notes

- Ensure your database is properly configured before running migrations.
