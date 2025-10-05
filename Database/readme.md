# PHP Database Manager
Lightweight standalone PDO connection manager.

![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue)  
![Issues](https://img.shields.io/github/issues/nivid42/PHP/Database)

---

## Overview
A lightweight and reusable **PDO connection manager** for PHP.  
Implements a simple singleton connection pattern, with support for custom factories and environment-based configuration.  
Ideal for small projects, scripts, or as a foundation for larger apps.

---

## Installation
Currently, there is no Composer package available.  
To use this connection manager:

1. Clone the repository or download the ZIP.  
2. Copy the `/Database` folder into your project.  
3. Include it manually

# Usage 
```php
require_once '/path/to/project/Database/Database.php';

putenv('DB_HOST=localhost');
putenv('DB_PORT=3306');
putenv('DB_NAME=my_app');
putenv('DB_USER=root');
putenv('DB_PASS=secret');

$pdo = Database::getConnection();

$stmt = $pdo->query('SELECT * FROM users');
$data = $stmt->fetchAll();

echo json_encode($data, JSON_PRETTY_PRINT);
```

---

# Features
🧠 Singleton connection pattern

🧪 Optional PDO factory (useful for testing or mock databases)

⚙️ Configurable PDO attributes

🧰 PSR-12 and IDE-friendly PHPDoc

❌ No dependencies

---


# Testing Example
``` php
Database::setPdoFactory(fn() => new PDO('sqlite::memory:'));

$pdo = Database::getConnection();

$pdo->exec('CREATE TABLE test (id INTEGER PRIMARY KEY, name TEXT)');
$pdo->exec("INSERT INTO test (name) VALUES ('Nivid')");

print_r($pdo->query('SELECT * FROM test')->fetchAll());

```

# Contributing 
Contributions are welcome! Feel free to:

- Submit issues for bugs or feature requests
- Open pull requests for improvements or new exception types
