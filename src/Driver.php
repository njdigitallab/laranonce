<?php

namespace NjDigitalLab\Laranonce;

use NjDigitalLab\Laranonce\Models\Nonce;

class Driver
{
    /**
     * Add the nonce.
     */
    public function add(string $name, string $salt, string $uuid)
    {
        Nonce::updateOrCreate(
            ['name' => $name, 'uuid' => $uuid],
            ['salt' => $salt]
        );
    }

    /**
     * Destroy the nonce.
     */
    public function remove(string $name, string $uuid): bool
    {
        return Nonce::where('name', $name)->where('uuid', $uuid)->delete();
    }

    /**
     * Find the nonce information.
     */
    public function getContents(string $name, string $uuid): array
    {
        $nonce = Nonce::where('name', $name)->where('uuid', $uuid)->first();

        if (is_null($nonce)) {
            return [];
        }

        return [
            'time' => $nonce->updated_at,
            'salt' => $nonce->salt,
        ];
    }
}