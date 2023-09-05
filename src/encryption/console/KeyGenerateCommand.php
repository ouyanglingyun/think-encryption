<?php

namespace think\encryption\console;

use think\console\Command;
use think\console\input\Option;
use think\encryption\Encrypter;

class KeyGenerateCommand extends Command
{

    protected function configure()
    {
        $this->setName('encrypter:generate')
            ->addOption('show', 's', Option::VALUE_NONE, 'Display the key instead of modifying files')
            ->addOption('force', 'f', Option::VALUE_NONE, 'Force the operation to run')
            ->addOption('env', 'e', Option::VALUE_OPTIONAL, 'Specify environment variable')
            ->setDescription('Set the encrypter key');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->generateRandomKey();

        if ($this->input->getOption('show')) {
            $this->output->writeln('<comment>' . $key . '</comment>');
        }

        if (!$this->setKeyInEnvironmentFile($key)) {

            return;
        }

        $cipher = $this->app->config->get('crypt.cipher');

        $this->app->config->set(['key' => $key, 'cipher' => $cipher], 'encrypter');

        $this->output->writeln('<info>encrypter key set successfully.</info>');
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:' . base64_encode(
            Encrypter::generateKey($this->app->config->get('crypt.cipher'))
        );
    }

    /**
     * Set the application key in the environment file.
     *
     * @param  string  $key
     * @return bool
     */
    protected function setKeyInEnvironmentFile($key)
    {
        $currentKey = $this->app->config->get('crypt.key');

        if (strlen($currentKey) !== 0 && (!$this->input->getOption('force'))) {
            $this->output->writeln('<error>The encryption key Settings have already been set.</error>');
            return false;
        }

        if (!$this->writeNewEnvironmentFileWith($key)) {
            return false;
        }

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return bool
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        $envName = $this->input->getOption('env');

        $envFile = $envName ? $this->app->getRootPath() . '.env.' . $envName : $this->app->getRootPath() . '.env';

        try {
            $input = file_get_contents($envFile);
        } catch (\Throwable $th) {
            $this->output->writeln("<error>Not found the .env.{$envName} file.</error>");
            return false;
        }

        $replaced = preg_replace(
            $this->keyReplacementPattern(),
            '[ENCRYPTER]' . PHP_EOL . 'KEY = ' . $key,
            $input
        );

        if ($replaced === $input || $replaced === null) {
            file_put_contents($envFile, PHP_EOL . PHP_EOL . '[ENCRYPTER]' . PHP_EOL . 'KEY = ' . $key, FILE_APPEND | LOCK_EX);
        } else {
            file_put_contents($envFile, $replaced, LOCK_EX);
        }

        return true;
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote($this->app->config->get('crypt.key'), '/');
        return "/^\[ENCRYPTER\]" . PHP_EOL . "KEY\s*=\s*{$escaped}/m";
    }
}
