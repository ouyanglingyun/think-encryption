# Encryption

<a name="introduction"></a>
## Introduction

ThinkPHP's encryption services provide a simple, convenient interface for encrypting and decrypting text via OpenSSL using AES-256 and AES-128 encryption.

<a name="installation"></a>
## Installation
You may install **think-encryption** via the Composer package manager:

    composer require lingyun/think-encryption


<a name="configuration"></a>
## Configuration

Before using ThinkPHP's encrypter, you must set the `key` configuration option in your `config/encrypter.php` configuration file. This configuration value is driven by the `ENCRYPTER.KEY` environment variable. You should use the `php think encrypter:generate` command to generate this variable's value since the `key:generate` command will use PHP's secure random bytes generator to build a cryptographically secure key for your application. 

<a name="using-the-encrypter"></a>
## Using The Encrypter

<a name="encrypting-a-value"></a>
#### Encrypting A Value

You may encrypt a value using the `encryptString` method provided by the `Crypt` facade. All encrypted values are encrypted using OpenSSL and the AES-256-CBC cipher.Furthermore, all encrypted values are signed with a message authentication code (MAC). The integrated message authentication code will prevent the decryption of any values that have been tampered with by malicious users:


    use think\facade\Crypt;
    use think\encryption\exception\EncryptException;

    try {
        $encrypted = Crypt::encryptString($valueToBeEncrypted);
    } catch (EncryptException $e) {
        //
    }

<a name="decrypting-a-value"></a>
#### Decrypting A Value

You may decrypt values using the `decryptString` method provided by the `Crypt` facade. If the value can not be properly decrypted, such as when the message authentication code is invalid, an `think\encryption\exception\DecryptException` will be thrown:

    use think\encryption\exception\DecryptException;
    use think\facade\Crypt;

    try {
        $decrypted = Crypt::decryptString($encryptedValue);
    } catch (DecryptException $e) {
        //
    }