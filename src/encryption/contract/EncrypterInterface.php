<?php

namespace lingyun\encryption\contract;

interface EncrypterInterface
{
    /**
     * Encrypt the given value.
     *
     * @param  mixed  $value
     * @param  bool  $serialize
     * @return string
     *
     * @throws \lingyun\encryption\exception\EncryptException
     */
    public function encrypt(mixed $value, bool $serialize = true): string;

    /**
     * Decrypt the given value.
     *
     * @param  string  $payload
     * @param  bool  $unserialize
     * @return mixed
     *
     * @throws \lingyun\encryption\exception\DecryptException
     */
    public function decrypt(string $payload, bool $unserialize = true): mixed;

    /**
     * Get the encryption key that the encrypter is currently using.
     *
     * @return string
     */
    public function getKey(): string;
}
