<?php

namespace NjDigitalLab\Laranonce;

use Illuminate\Support\Facades\Auth;
use NjDigitalLab\Laranonce\Driver;

class HashService
{
    /**
     * Create an instance of the service.
     */
    public function __construct()
    {
        $this->driver = new Driver;
    }

    /**
     * Generate a nonce.
     */
    public function generate(string $name): string
    {
        $salt = $this->generateSalt();

        $this->driver->add($name, $salt, $this->getUuid());

        return $this->generateHash($name, $salt);
    }

    /**
     * Verify the nonce.
     */
    public function verify(string $name, string $hash): bool
    {
        if (Laranonce::isDisabled()) {
            return true;
        }

        $contents = $this->driver->getContents($name, $this->getUuid());
        $salt = $contents['salt'] ?? '';
        $time = $contents['time'] ?? '';

        if (empty($contents)) {
            return false;
        }

        if ($this->hashIsExpired($time)) {
            $this->driver->remove($name, $this->getUuid());
            return false;
        }

        if ($this->hashMatches($name, $hash, $salt)) {
            $this->driver->remove($name, $this->getUuid());
            return true;
        }

        return false;
    }

    /**
     * Generate a salt.
     */
    protected function generateSalt(): string
    {
        return bin2hex(random_bytes(64));
    }

    /**
     * Generate the hash.
     */
    protected function generateHash(string $name, string $salt): string
    {
        $secret = Laranonce::secret();
        $message = $name.'|'.$secret.'|'.$salt.'|'.$this->getUuid();
        
        return hash(Laranonce::algorithm(), $message);
    }

    /**
     * Check if the hash is valid.
     */
    protected function hashMatches(string $name, string $hash, string $salt): bool
    {
        return $this->generateHash($name, $salt) === $hash;
    }

    /**
     * Check if the hash is expired.
     */
    protected function hashIsExpired(string $time): bool
    {
        $expirationTimestamp = strtotime($time) + Laranonce::lifetime();

        if (now()->timestamp > $expirationTimestamp) {
            return true;
        }

        return false;
    }

    /**
     * Get a UUID.
     */
    protected function getUuid(): string
    {
        return session()->getId();
    }
}
