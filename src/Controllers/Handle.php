<?php

namespace Boytunghc\LaravelGitHook\Controllers;

//use App\Http\Controllers\Controller;

use Monolog\Logger;
use Illuminate\Http\Request;
use Monolog\Handler\StreamHandler;
use Boytunghc\LaravelGitHook\Contracts\HookContract;

class Handle
{
    public function gitHook(Request $request, HookContract $hook)
    {
        $log = new Logger('githook');
        $log->pushHandler(new StreamHandler(storage_path('logs/githook.log')));

        //check IP
        if (! $this->isTrustIp($request)) {
            $msg = 'Request must come from an approved IP';
            $log->addRecord(Logger::ERROR, $msg);
            return response([
                'success' => false,
                'message' => $msg,
            ], 401);
        }

        // verify deploy key if enable
        if (config('githook.secret') && !$hook->verifySecret()) {
            $msg = 'Secret did not match';
            $log->addRecord(Logger::ERROR, $msg);
            return response([
                'success' => false,
                'message' => $msg,
            ], 401);
        }

        $payload = $hook->payload;

        // check payload
        if ($payload === null) {
            $msg = 'Web hook data does not look valid';
            $log->addRecord(Logger::ERROR, $msg);
            return response([
                'success' => false,
                'message' => $msg,
            ], 400);
        }

        $localBranch = trim(`git rev-parse --abbrev-ref HEAD`);

        if ($localBranch !== $hook->branch()) {
            $msg = 'Pushed refs do not match current branch';
            $log->addRecord(Logger::INFO, $msg);
            return response([
                'success' => false,
                'message' => $msg,
            ]);
        }

        chdir('..'); // change working dir to root of project
        $this->execCommands($this->buildCommands(), $log);

        $msg = 'Deploy success with ' . count($hook->commits() ?? []) . ' commits';

        $log->addRecord(Logger::INFO, $msg);

       return response([
           'success' => true,
           'message' => $msg
       ]);

    }

    /**
     * Limit to known servers
     *
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    private function isTrustIp(Request $request)
    {
        if (config('githook.allowed_sources', []) === []) {
            return true;
        }

        return in_array($request->ip(), config('githook.')) ? true : false;
    }

    /**
     * Make list command to execute
     *
     * @return string[]
     */
    private function buildCommands()
    {
        $commands = array_merge(
            config('githook.before_pull'),
            ['git pull'],
            config('githook.after_pull')
        );

        return array_map(function ($cmd) {
            return trim($cmd);
        }, $commands);
    }

    /**
     * Execute list command
     *
     * @param array $commands
     * @param \Monolog\Logger $log
     * @return string[] output console
     */
    private function execCommands(array $commands = [], Logger $logger)
    {
        foreach ($commands as $cmd) {
            exec("$cmd 2>&1", $output);

            $logger->addRecord($logger::INFO, "Executed command: \"$cmd\"");
            $this->logMany($output, $logger, $logger::DEBUG);
            unset($output);
        }
    }

    protected function logMany(array $logs, Logger $logger, int $type)
    {
        foreach ($logs as $log) {
            if (!empty($log)) {
                $logger->addRecord($type, $log);
            }
        }
    }
}
