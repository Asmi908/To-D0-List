#Readme
 ToDoListApp

A simple custom-MVC PHP “To-Do List” application with categories, tasks, deadlines, and notes. Built on top of PHP 8.x, MariaDB/MySQL, and Composer for dependency/autoload management. No external PHP framework is used—everything is handcrafted in a lightweight MVC style.

---

## Table of Contents

1. [Features](#features)  
2. [Technologies](#technologies)  
3. [Requirements](#requirements)  
4. [Project Structure](#project-structure)  
5. [Installation & Setup](#installation--setup)  
   1. [Clone the Repository](#clone-the-repository)  
   2. [Composer Dependencies](#composer-dependencies)  
   3. [Database Import](#database-import)  
   4. [Configure Database Credentials](#configure-database-credentials)  
   5. [Run the App](#run-the-app)  
6. [Usage](#usage)  
7. [Running Tests (Optional)](#running-tests-optional)  


---

## Features

- **User Management** (create users with hashed passwords, toggle active/inactive)
- **Categories** (each user can create/edit/delete categories)
- **Tasks** (each task belongs to a user + category, with title, status, importance)
- **Deadlines** (assign deadlines to tasks, with optional status/priority)
- **Notes** (`tb_note` table to add textual notes to tasks)
- **Soft-delete flag** (`is_deleted` column in `tb_note`)
- **Simple authentication** (cookie/session-based with hashed passwords)
- **MVC pattern** (Models, Views, Controllers organized by hand—no framework)

---

## Technologies

- **PHP 8.x**  
- **MariaDB/MySQL 10.4.x** (or higher)  
- **Composer** (for autoloading and dev-dependencies)  
- **XAMPP** (Apache + PHP + MariaDB bundled for local development)  
- **phpMyAdmin** (optional GUI for inspecting the database)

---

## Requirements

- **Windows (or macOS/Linux)** with PHP 8.x installed (or use XAMPP).  
- **Apache** (bundled in XAMPP) running on port 80 (or other).  
- **MySQL/MariaDB** server running on port 3306 (default) or 3307 (as configured).  
- **Composer** (https://getcomposer.org/) in your PATH.  
- (Optional) **phpMyAdmin** to inspect the database interactively.

---

## Project Structure

ToDoListApp/
├── app/
│ ├── config/
│ │ └── config.php# Database credentials (host, user, pass, dbname)
       database.php 
│ ├── core/
│ │ ├── Core.php # PDO singleton
│ │ └── BaseController.php # Generic CRUD methods
│ ├── controllers/
│ │ ├── AuthController.php # Login/logout, session handling
│ │ ├── TaskController.php # Task CRUD + status/importance
│ │ ├── CategoryController.php
│ │ ├── DeadlineController.php
│ │ └── NoteController.php
│ ├── models/
│ │ ├── User.php
│ │ ├── Task.php
│ │ ├── Category.php
│ │ ├── Deadline.php
│ │ └── Note.php
│ └── views/
│ ├── auth/ # login.php, register.php
│ ├── tasks/ # index.php, create.php, edit.php
│ ├── categories/ # index.php, create.php, edit.php
│ ├── deadlines/ # index.php, create.php, edit.php
│ └── notes/ # index.php, create.php, edit.php
├── database/
│ └── schema.sql # Full SQL dump (tables + data)
├── public/
│ └── index.php # Front controller (entry point)
├── scripts/
│ └── run_migrations.php # (Optional) Runs incremental migrations
├── tests/ # (Optional) PHPUnit tests
│ └── ExampleTest.php (Category,Task,Note,Deadline and Users)
├── vendor/ # Composer-managed dependencies & autoload
├── .gitignore
├── composer.json
├── composer.lock
└── README.md

yaml
Copy
Edit

---

## Installation & Setup

Follow these steps to get the app running locally on XAMPP (Windows). Adjust paths if on macOS/Linux.

### 1. Clone the Repository

Open a terminal (Git Bash, PowerShell, or Command Prompt) and run:

```bash
cd C:/xampp/htdocs/
git clone <your-repo-URL> ToDoListApp
cd ToDoListApp
Now you should be in:

swift
Copy
Edit
C:/xampp/htdocs//ToDoListApp
2. Composer Dependencies
If you plan to use PHPUnit (or any other libraries), install Composer dependencies:

bash
Copy
Edit
composer install
This will create the vendor/ directory containing the autoloader and any dev-dependencies.

If you don’t need tests, you can skip this; the project’s core simply relies on vendor/autoload.php to load classes in app/.

3. Database Import
Start XAMPP

Launch the XAMPP Control Panel.

Start both Apache and MySQL modules.

Create the database

Open phpMyAdmin (http://localhost/phpmyadmin) 

In phpMyAdmin:

Click New → name it todolist_app → click Create.



In phpMyAdmin:

Select the todolist_app database in the left sidebar.

Click Import → Choose the file ToDoListApp/database/schema.sql → Click Go.

Or, via Git Bash / PowerShell:

bash
Copy
Edit
cd /c/xampp/htdocs/skeletontodo/ToDoListApp
mysql -u root -p todolist_app < database/schema.sql
When prompted for password, leave blank (default XAMPP root has no password) or enter your root password if you set one.

4. Configure Database Credentials
Open app/config/database.php and edit to match your local MySQL setup. Example:

php
Copy
Edit
<?php
return [
    'driver'   => 'mysql',
    'host'     => '127.0.0.1',      // or 'localhost'
    'dbname'   => 'todolist_app',
    'charset'  => 'utf8mb4',
    'username' => 'root',
    'password' => '',               // XAMPP default is empty
];
If MySQL listens on port 3307, add 'port' => '3306' inside the array.

Save changes.

5. Run the App
Point your browser to

bash
Copy
Edit
http://localhost/skeletontodo/ToDoListApp/public/
The front controller (public/index.php) bootstraps your MVC app:

php
Copy
Edit
<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/database.php';
// … load core files, controllers, etc. …
// Example: (pseudo-code)
// $router = new Router();
// $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
Register / Log in

If no users exist, register a new user or manually insert one into the users table.

After logging in, you can create categories, tasks, deadlines, and notes.



Running Tests (Optional)
If you installed PHPUnit via Composer, you can run unit tests:

Ensure PHPUnit is installed (composer require --dev phpunit/phpunit).

Add an autoload-dev section to composer.json (if not already present):

json
Copy
Edit
"autoload-dev": {
  "psr-4": {
    "Tests\\": "tests/"
  }
}
Run:

bash
Copy
Edit
composer dump-autoload
vendor/bin/phpunit
PHPUnit will look for phpunit.xml (if provided) or run any files under tests/ ending in Test.php
