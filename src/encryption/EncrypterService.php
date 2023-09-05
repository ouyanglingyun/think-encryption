<?php

namespace think\encryption;

use think\encryption\Encrypter;
use think\encryption\console\KeyGenerateCommand;
use think\encryption\contract\EncrypterInterface;
use think\encryption\contract\StringEncrypterInterface;
use think\encryption\exception\MissingAppKeyException;
use think\helper\Str;

class EncrypterService extends \think\Service

{
    public function register()
    {
        $this->app->bind('encrypter', function () {
            return $this->makeEncrypter();
        });
    }

    protected function makeEncrypter()
    {
        $config = $this->app->config->get('crypt', []);
        return new Encrypter($this->parseKey($config), $config['cipher']);
    }

    public function boot()
    {
        $this->commands([
            KeyGenerateCommand::class,
        ]);

        $this->app->bind(EncrypterInterface::class, function () {
            return app('encrypter');
        });

        $this->app->bind(StringEncrypterInterface::class, function () {
            return app('encrypter');
        });
    }

    /**
     * Parse the encryption key.
     *
     * @param  array  $config
     * @return string
     */
    protected function parseKey(array $config)
    {
        if (Str::startsWith($key = $this->key($config), $prefix = 'base64:')) {
            $key = base64_decode(array_reverse(explode($prefix, $key, 2))[0]);
        }
        return $key;
    }

    protected function key(array $config)
    {
        return tap($config['key'], function ($key) {
            if (empty($key)) {
                throw new MissingAppKeyException;
            }
        });
    }
}
