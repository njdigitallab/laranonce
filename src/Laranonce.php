<?php

namespace NjDigitalLab\Laranonce;

use NjDigitalLab\Laranonce\HashService;

class Laranonce
{
    /**
     * If the package is enabled.
     */
    private static bool $enable = true;

    /**
     * How long the nonce is valid for in seconds.
     */
    private static int $lifetime = 900;

    /**
     * The table name.
     */
    private static string $tableName = 'laranonces';

    /**
     * Disable the package.
     */
    public static function disable(): void
    {
        static::$enable = false;
    }

    /**
     * Check if the package is enabled.
     */
    public static function isEnabled(): bool
    {
        return static::$enable;
    }

    /**
     * Check if the package is disabled.
     */
    public static function isDisabled(): bool
    {
        return static::$enable === false;
    }

    /**
     * Get the hashing algorithm.
     */
    public static function algorithm(): string
    {
        return 'sha256';
    }

    /**
     * Get the secret to use in the hashing algorithm.
     */
    public static function secret(): ?string
    {
        return config('app.key');
    }

    /**
     * set the lifetime in seconds that a nonce will be valid for.
     */
    public static function setLifetime(int $lifetime): void
    {
        static::$lifetime = $lifetime;
    }

    /**
     * Get the lifetime that the nonce is valid for in seconds.
     */
    public static function lifetime(): int
    {
        return static::$lifetime;
    }

    /**
     * Set the tablename.
     */
    public static function setTableName(string $tableName): void
    {
        static::$tableName = $tableName;
    }

    /**
     * Get the table name.
     */
    public static function tableName(): string
    {
        return static::$tableName;
    }

    /**
     * Generate a nonce.
     */
    public static function generate(string $name): string
    {
        return new HashService()->generate($name);
    }

    /**
     * Verify a nonce.
     */
    public static function verify(string $name, string $hash): bool
    {
        return new HashService()->verify($name, $hash);
    }

    /**
     * Generate a nonce field.
     */
    public static function generateField(string $action, string $name = 'nonce'): string
    {
        return "<input type='hidden' name='".$name."' value=".Laranonce::generate($action).">";
    }
}
