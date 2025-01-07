# Project Setup Guide

This guide will walk you through setting up and running the project with Docker.

## Prerequisites

Make sure you have Docker and Docker Compose installed on your machine.

- **[Install Docker](https://docs.docker.com/get-started/get-docker/)**

## Getting Started

Follow these steps to get your local environment set up.

### 1. Clone the Repository

First, clone the repository to your local machine.

```bash
git clone https://github.com/Gurinder-Batth/task-management-todo
cd task-management-todo
```

### 2. Set Up the Docker Environment

In the root directory of the project, run the following command to start the Docker containers:

```bash
docker compose up -d
```

This will set up the necessary containers in detached mode.

### 3. Configure the Database

Before running the application, you'll need to configure the database details in your `.env` file.

Update the `.env` file with the following database connection settings:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=password
```

These values will connect your Laravel application to the MySQL database running in the Docker container.

### 4. Install Dependencies

Once the containers are up, install the project dependencies using Composer:

```bash
docker compose exec app composer install
```

### 5. Run Migrations

To set up the database tables, run the following migration command:

```bash
docker compose exec app php artisan migrate
```

This will create the necessary database tables for the application.

### 6. Access the Application

Once everything is set up, open your browser and navigate to:

```bash
http://localhost:8001/tasks
```

You should now be able to see the Task Manager application running.

---

## Troubleshooting

- **Container not starting**: If the containers don't start, try running `docker compose down` and then `docker compose up -d` to restart everything.
- **Database connection issues**: Double-check the `.env` database settings and make sure the database container is running.
