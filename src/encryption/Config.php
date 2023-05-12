<?php
/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| This key is used by the  encrypter service and should be set
| to a random, 32 character string, otherwise these encrypted strings
| will not be safe. Please do this before deploying an application!
|
*/
return [
    'key'    => env('encrypter.key', ''),
    'cipher' => env('encrypter.cipher', 'AES-256-CBC'),
];
