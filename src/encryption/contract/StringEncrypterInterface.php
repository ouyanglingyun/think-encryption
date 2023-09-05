<?php

namespace think\encryption\contract;

interface StringEncrypterInterface
{
    /**
     * Encrypt a string without serialization.
     *
     * @param  mixed  $value
     * @return string
     *
     * @throws \think\encryption\exception\EncryptException
     */
    public function encryptString(mixed $value): string;

    /**
     * Decrypt the given string without unserialization.
     *
     * @param  string  $payload
     * @return string
     *
     * @throws \think\encryption\exception\DecryptException
     */
    public function decryptString(string $payload): string;
}
