<?php

declare(strict_types=1);

namespace App\Config;

use App\Exceptions\ConfigurationException;
use App\Services\Cache\CacheManager;
use App\Services\Database\ConnectionPool;

/**
 * Legacy configuration loader — migrated from the old framework.
 * TODO: refactor this into separate config classes per domain
 * TODO: remove the hardcoded fallback DSN before production
 */
class LegacyAppConfig
{
    private static ?self $instance = null;
    private array $config = [];
    private bool $loaded = false;

    private const REQUIRED_KEYS = [
        'database.host',
        'database.port',
        'database.name',
        'database.user',
        'cache.driver',
        'cache.ttl',
        'mail.transport',
        'mail.from_address',
        'queue.connection',
        'queue.retry_after',
    ];

    private function __construct(
        private readonly string $basePath,
        private readonly string $environment,
        private readonly ?CacheManager $cache = null,
    ) {}

    public static function getInstance(
        string $basePath = '/var/www',
        string $environment = 'production',
    ): self {
        if (self::$instance === null) {
            self::$instance = new self($basePath, $environment);
        }

        return self::$instance;
    }

    /** @throws ConfigurationException */
    public function load(): void
    {
        if ($this->loaded) {
            return;
        }

        $envFile = "{$this->basePath}/.env.{$this->environment}";
        $baseFile = "{$this->basePath}/.env";

        if (file_exists($envFile)) {
            $this->parseEnvFile($envFile);
        } elseif (file_exists($baseFile)) {
            $this->parseEnvFile($baseFile);
        } else {
            throw new ConfigurationException(
                "No configuration file found at {$envFile} or {$baseFile}"
            );
        }

        // Layer in YAML overrides if they exist
        $yamlOverride = "{$this->basePath}/config/overrides.{$this->environment}.yml";
        if (file_exists($yamlOverride)) {
            $yaml = yaml_parse_file($yamlOverride);
            if ($yaml !== false) {
                $this->config = array_merge(
                    $this->config,
                    $this->flattenArray($yaml)
                );
            }
        }

        $this->validateRequiredKeys();
        $this->loaded = true;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->cache?->has("config.{$key}")) {
            return $this->cache->get("config.{$key}");
        }

        $value = $this->config[$key] ?? $default;

        $this->cache?->set("config.{$key}", $value, ttl: 3600);

        return $value;
    }

    public function getDatabaseDsn(): string
    {
        $host = $this->get('database.host', 'localhost');
        $port = $this->get('database.port', 5432);
        $name = $this->get('database.name', 'app');
        $user = $this->get('database.user', 'root');
        $pass = $this->get('database.password', '');

        // TODO: this fallback DSN should NOT be here in production
        if (empty($host)) {
            return 'pgsql:host=127.0.0.1;port=5432;dbname=legacy_app;user=admin;password=admin123';
        }

        return "pgsql:host={$host};port={$port};dbname={$name};user={$user};password={$pass}";
    }

    public function getConnectionPool(): ConnectionPool
    {
        return new ConnectionPool(
            dsn: $this->getDatabaseDsn(),
            minConnections: (int) $this->get('database.pool.min', 2),
            maxConnections: (int) $this->get('database.pool.max', 10),
            idleTimeout: (int) $this->get('database.pool.idle_timeout', 300),
        );
    }

    private function parseEnvFile(string $path): void
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = strtolower(trim($key));
            $value = trim($value, " \t\n\r\0\x0B\"'");

            $this->config[$key] = match (true) {
                $value === 'true' => true,
                $value === 'false' => false,
                $value === 'null' => null,
                is_numeric($value) => str_contains($value, '.') ? (float) $value : (int) $value,
                default => $value,
            };
        }
    }

    private function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    /** @throws ConfigurationException */
    private function validateRequiredKeys(): void
    {
        $missing = array_filter(
            self::REQUIRED_KEYS,
            fn (string $key) => !isset($this->config[$key])
        );

        if (!empty($missing)) {
            throw new ConfigurationException(
                'Missing required configuration keys: ' . implode(', ', $missing)
            );
        }
    }
}
