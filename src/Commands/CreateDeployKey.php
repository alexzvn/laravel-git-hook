<?php

namespace Boytunghc\LaravelGitHook\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateDeployKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'githook:key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make random key for git hook';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->writeNewEnvironmentFileWith(Str::random());
    }

    /**
     * Write a new environment file with the given keys.
     *
     * @param  string $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith(string $key)
    {
        $contents = file_get_contents($this->laravel->environmentFilePath());

        if (! Str::contains($contents, 'GIT_DEPLOY_KEY')) {
            $contents .= PHP_EOL.'GIT_DEPLOY_KEY=';
        }

        $contents = preg_replace(
            $this->keyReplacementPattern(),
            'GIT_DEPLOY_KEY='.$key,
            $contents
        );

        file_put_contents($this->laravel->environmentFilePath(), $contents);
    }

    /**
     * Get a regex pattern that will match env $keyName with any key.
     *
     * @param  string $keyName
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $key = $this->laravel['config']['githook.secret_key'];

        $escaped = preg_quote('='.$key, '/');

        return "/^{GIT_DEPLOY_KEY}{$escaped}/m";
    }
}
