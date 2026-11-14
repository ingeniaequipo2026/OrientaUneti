<?php
/**
 * Conexión a la Base de Datos con PDO (PostgreSQL / MySQL / Render)
 * OrientaUneti - Plataforma de Bienestar Estudiantil
 */

class Database {
    private static ?PDO $instance = null;
    private static string $activeDriver = 'pgsql';

    public static function getConnection(): PDO {
        if (self::$instance !== null) {
            return self::$instance;
        }

        self::loadEnv();

        // 1. Prioridad: DATABASE_URL (Formato estándar en Render / Heroku / Supabase para PostgreSQL)
        $databaseUrl = getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?? null);

        if ($databaseUrl) {
            $dbParts = parse_url($databaseUrl);
            $driver = 'pgsql';
            self::$activeDriver = 'pgsql';
            $host = $dbParts['host'] ?? 'localhost';
            $port = $dbParts['port'] ?? 5432;
            $user = $dbParts['user'] ?? '';
            $pass = $dbParts['pass'] ?? '';
            $dbname = ltrim($dbParts['path'] ?? '', '/');
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=prefer";

            self::$instance = new PDO($dsn, $user ?? '', $pass ?? '', [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            return self::$instance;
        }

        // 2. Variables de entorno individuales
        $driver = getenv('DB_DRIVER') ?: ($_ENV['DB_DRIVER'] ?? 'mysql');
        $host   = getenv('DB_HOST')   ?: ($_ENV['DB_HOST']   ?? 'localhost');
        $dbname = getenv('DB_NAME')   ?: ($_ENV['DB_NAME']   ?? 'orientauneti');
        $user   = getenv('DB_USER')   ?: ($_ENV['DB_USER']   ?? 'root');
        $pass   = getenv('DB_PASS')   ?: ($_ENV['DB_PASS']   ?? '');

        if ($driver === 'pgsql') {
            $port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '5432');
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        } else {
            $port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '3306');
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        }

        self::$activeDriver = $driver;

        try {
            self::$instance = new PDO($dsn, $user ?? '', $pass ?? '', [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            return self::$instance;
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos ({$driver}): " . $e->getMessage());

            // Si intentó conectar a PostgreSQL y falló, pero MySQL está disponible en XAMPP, reintentar con MySQL
            if ($driver === 'pgsql') {
                try {
                    $mysqlDsn = "mysql:host=localhost;port=3306;dbname={$dbname};charset=utf8mb4";
                    self::$instance = new PDO($mysqlDsn, 'root', '', [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]);
                    self::$activeDriver = 'mysql';
                    return self::$instance;
                } catch (Exception $e2) {
                    // Ignorar y mostrar vista de ayuda
                }
            }

            if (php_sapi_name() !== 'cli') {
                http_response_code(500);
                require_once __DIR__ . '/../views/errors/db_error.php';
                exit;
            }
            throw $e;
        }
    }

    public static function getActiveDriver(): string {
        return self::$activeDriver;
    }

    private static function loadEnv(): void {
        $envFile = __DIR__ . '/../.env';
        if (!file_exists($envFile)) {
            return;
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                [$key, $val] = explode('=', $line, 2);
                $key = trim($key);
                $val = trim($val, " \t\n\r\0\x0B\"'");
                if (!getenv($key)) {
                    putenv("{$key}={$val}");
                    $_ENV[$key] = $val;
                }
            }
        }
    }
}
