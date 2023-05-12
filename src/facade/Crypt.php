<?php

namespace lingyun\facade;

use think\Facade;

/**
 * @method static bool supported(string $key, string $cipher)
 * @method static string generateKey(string $cipher)
 * @method static string encrypt(mixed $value, bool $serialize = true)
 * @method static string encryptString(string $value)
 * @method static mixed decrypt(string $payload, bool $unserialize = true)
 * @method static string decryptString(string $payload)
 * @method static string getKey()
 *
 * @see \lingyun\encryption\Encrypter
 */
class Crypt extends Facade
{
    protected static function getFacadeClass()
    {
        return 'encrypter';
    }
}
