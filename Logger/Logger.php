<?php

/**
 * Logger class
 *
 * Provides a lightweight file-based logging system with support for
 * different log levels (DEBUG, INFO, WARNING, ERROR), automatic log rotation,
 * and environment-based configuration.
 *
 */
class Logger
{
    /** @var int Debug level (detailed development information) */
    public const DEBUG   = 100;

    /** @var int Info level (general runtime information) */
    public const INFO    = 200;

    /** @var int Warning level (non-critical issues or unusual states) */
    public const WARNING = 300;

    /** @var int Error level (critical errors that require attention) */
    public const ERROR   = 400;

    /** @var array Maps log level constants to their string representations */
    private const LEVEL_NAMES = [
        self::DEBUG => 'DEBUG',
        self::INFO => 'INFO',
        self::WARNING => 'WARNING',
        self::ERROR => 'ERROR'
    ];

    /** @var int Maximum log file size before rotation (default: 25 MB) */
    private static int $maxFileSize = 25 * 1024 * 1024;

    /** @var int Number of old log files to keep after rotation (default: 10) */
    private static int $maxFiles = 10;

    /**
     * Returns the path to the log file.
     *
     * Uses the environment variable `LOG_FILE` if set,
     * otherwise falls back to `../../logs/event.log`.
     *
     * @return string Absolute path to the log file.
     */
    private static function getLogFile(): string
    {
        return getenv('LOG_FILE') ?: __DIR__ . '/../../logs/event.log';
    }

    /**
     * Determines the currently active log level from the `LOG_LEVEL` environment variable.
     *
     * Supported values:
     * - DEBUG
     * - INFO
     * - WARNING
     * - ERROR
     *
     * Defaults to `INFO` if not defined or invalid.
     *
     * @return int The currently active log level constant.
     */
    private static function getActiveLogLevel(): int
    {
        $level = strtoupper(getenv('LOG_LEVEL') ?: 'INFO');
        return match ($level) {
            'DEBUG' => self::DEBUG,
            'INFO' => self::INFO,
            'WARNING' => self::WARNING,
            'ERROR' => self::ERROR,
            default => self::INFO
        };
    }

    /**
     * Checks if the log file exceeds the maximum allowed size and rotates it if necessary.
     *
     * When rotated the file is renamed with a timestamp suffix and
     * only the last {@see self::$maxFiles} rotated files are kept.
     *
     * @param string $logFile Full path to the log file to check and rotate.
     *
     * @return void
     */
    private static function rotateLogs(string $logFile): void
    {
        if (!file_exists($logFile) || filesize($logFile) < self::$maxFileSize) {
            return;
        }

        $timestamp = date('Ymd_His');
        $rotatedFile = $logFile . '.' . $timestamp;
        rename($logFile, $rotatedFile);

        $logDir = dirname($logFile);
        $pattern = basename($logFile) . '.*';
        $files = glob($logDir . '/' . $pattern) ?: [];

        usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));

        foreach (array_slice($files, self::$maxFiles) as $file) {
            @unlink($file);
        }
    }

    /**
     * Writes a message to the log file.
     *
     * If no log level is provided `INFO` is used by default.
     * Messages with a lower level than the configured system level are ignored.
     *
     * @param string $message  The log message to write.
     * @param int|null $level  Optional log level constant
     *                         (e.g. {@see self::DEBUG}, {@see self::INFO}).
     *                         Defaults to {@see self::INFO}.
     *
     * @return void
     *
     * @throws Throwable (Internally caught; documented for completeness.)
     */
    public static function log(string $message, ?int $level = null): void
    {
        try {
            $logFile = self::getLogFile();
            $systemLevel = self::getActiveLogLevel();
            $messageLevel = $level ?? self::INFO;

            if ($messageLevel < $systemLevel) {
                return;
            }

            $logDir = dirname($logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0775, true);
            }

            if (!is_writable($logDir)) {
                error_log("Logger Error: Log directory is not writable.");
                return;
            }

            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller = $backtrace[1] ?? [];

            $file     = isset($caller['file']) ? basename($caller['file']) : 'unknown';
            $line     = $caller['line'] ?? '??';
            $function = $caller['function'] ?? 'global';

            $timestamp = gmdate('Y-m-d H:i:s');
            $levelName = self::LEVEL_NAMES[$messageLevel] ?? 'INFO';

            $logEntry = sprintf("[%s] [%s][%s:%s][%s] %s%s",
                $timestamp,
                $levelName,
                $file,
                $line,
                $function,
                $message,
                PHP_EOL
            );

            self::rotateLogs($logFile);
            file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

        } catch (Throwable $e) {
            error_log("Logger Exception: " . $e->getMessage());
        }
    }
}
