# PHP Exception Library

![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue)  
![Issues](https://img.shields.io/github/issues/nivid42/PHP/Exceptions)  

# Overview
A collection of reusable and extensible Exception Classes for PHP, designed to make error handling more consistent and structured.

---

# Installation
Currently, there is no Composer package available. To use this library:

1. Clone the repository or download the ZIP.
2. Copy the Exceptions folder into your project.

---

# Usage
Include the exception classes manually:
```PHP
require_once '/path/to/project/Exceptions/BaseException.php';
require_once '/path/to/project/Exceptions/ValidationException.php';
require_once '/path/to/project/Exceptions/NotFoundException.php';

try {
    throw new ValidationException("User-ID fehlt");
} catch (ValidationException $e) {
    echo json_encode($e->toArray());
} catch (NotFoundException $e) {
    echo "Not Found: " . $e->getMessage();
} catch (\Throwable $e) {
    echo "Unexpected error: " . $e->getMessage();
}
```

---

# Features

- Consistent structure for exceptions (message, code, errorCode, httpCode)
- toArray() method for easy JSON serialization (useful for APIs)
- Easily extendable with project-specific exceptions
- Compatible with PHP >= 7.4

---

# Contributing 
Contributions are welcome! Feel free to:

- Submit issues for bugs or feature requests
- Open pull requests for improvements or new exception types
