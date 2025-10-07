# Standalone Logging Class
Lightweight PHP logging class with automatic log rotation and configurable debug levels

![PHP Version](https://img.shields.io/badge/php-%3E%3D8.0-blue)  
![Issues](https://img.shields.io/github/issues/nivid42/PHP/Logger)

---

## Overview
A lightweight and reusable **file-based logging system** for PHP.  
Features automatic log rotation, multiple log levels, environment-based configuration, and detailed call context tracking.  
Ideal for small to medium projects, scripts, or as a foundation for larger applications.

---

## Installation
Currently, there is no Composer package available.  
To use this logger:

1. Clone the repository or download the ZIP.  
2. Copy the `Logger.php` file into your project.  
3. Include it manually or via your autoloader.

---

## Usage 

### Basic Example
```php
require_once '/path/to/Logger.php';

// Configure via environment variables (optional)
putenv('LOG_FILE=/var/log/myapp/events.log');
putenv('LOG_LEVEL=DEBUG');

// Log messages at different levels
Logger::log('Application started', Logger::INFO);
Logger::log('User authentication successful', Logger::DEBUG);
Logger::log('Deprecated function called', Logger::WARNING);
Logger::log('Database connection failed', Logger::ERROR);
```

### Quick Start
```php
// Without configuration, logs to ../../logs/event.log with INFO level
Logger::log('This is a simple log message');
```

---

## Features
📊 **Four log levels**: DEBUG, INFO, WARNING, ERROR

🔄 **Automatic log rotation**: Rotates logs when they exceed 25 MB

📁 **Configurable retention**: Keeps last 10 rotated log files by default

🎯 **Call context tracking**: Automatically captures file, line, and function names

⚙️ **Environment-based config**: Easy configuration via environment variables

🔒 **Thread-safe writing**: Uses file locking to prevent race conditions

❌ **No dependencies**: Pure PHP, no external libraries required

---

## Configuration

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `LOG_FILE` | Full path to the log file | `../../logs/event.log` |
| `LOG_LEVEL` | Minimum log level to record | `INFO` |

### Log Levels
Messages are only logged if their level meets or exceeds the configured `LOG_LEVEL`:

- **DEBUG** (100): Detailed development information
- **INFO** (200): General runtime information
- **WARNING** (300): Non-critical issues or unusual states
- **ERROR** (400): Critical errors requiring attention

### Example Configuration
```php
// Set custom log location
putenv('LOG_FILE=/var/log/myapp/events.log');

// Only log warnings and errors (suppress DEBUG and INFO)
putenv('LOG_LEVEL=WARNING');

Logger::log('This will not be logged', Logger::INFO);
Logger::log('This will be logged', Logger::ERROR);
```

---

## Log Format
Each log entry includes:
- Timestamp (UTC)
- Log level
- Source file and line number
- Function/method name
- Your message

Example output:
```
[2025-10-07 14:23:45] [INFO][index.php:42][main] Application started successfully
[2025-10-07 14:23:46] [ERROR][UserController.php:89][authenticate] Invalid credentials provided
```

---

## Log Rotation
The logger automatically rotates files when they exceed 25 MB:

1. Current log file is renamed with a timestamp (e.g., `event.log.20251007_142345`)
2. A new log file is created
3. Only the 10 most recent rotated files are kept
4. Older files are automatically deleted

This prevents disk space issues and keeps your logs manageable.

---

## Advanced Usage

### Custom Log Levels
```php
// Debug detailed information during development
Logger::log('Variable dump: ' . print_r($data, true), Logger::DEBUG);

// Info for general application flow
Logger::log('User logged in: ' . $username, Logger::INFO);

// Warnings for recoverable issues
Logger::log('API rate limit approaching', Logger::WARNING);

// Errors for critical failures
Logger::log('Payment processing failed: ' . $error, Logger::ERROR);
```

### Error Handling
The logger gracefully handles failures:
- Creates log directory if it doesn't exist
- Falls back to PHP's `error_log()` if writing fails
- Catches all exceptions to prevent application crashes

---

## Testing Example
```php
// Set to a test directory
putenv('LOG_FILE=/tmp/test.log');
putenv('LOG_LEVEL=DEBUG');

// Generate test logs
Logger::log('Test message 1', Logger::DEBUG);
Logger::log('Test message 2', Logger::INFO);
Logger::log('Test message 3', Logger::WARNING);
Logger::log('Test message 4', Logger::ERROR);

// Read the log file
echo file_get_contents('/tmp/test.log');

// Clean up
unlink('/tmp/test.log');
```

---

## Requirements
- PHP 8.0 or higher (cuz of match)
- Write permissions for the log directory
- Sufficient disk space for log files

---

## Contributing 
Contributions are welcome! Feel free to:

- Submit issues for bugs or feature requests
- Open pull requests for improvements
- Suggest new features or log levels
- Improve documentation

---

## Author
Created by [nivid42](https://github.com/nivid42)

For more PHP utilities, check out the [PHP project collection](https://github.com/nivid42/PHP).
