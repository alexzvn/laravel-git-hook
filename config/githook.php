<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Drive
    |--------------------------------------------------------------------------
    | 
    | Drive contains name, where is your vendor remote stored
    | For now support only: github, gitlab
    |
    */

    'drive' => 'github',


    /*
    |--------------------------------------------------------------------------
    | Allowed sources
    |--------------------------------------------------------------------------
    |
    | A request will be ignored unless it comes from an IP listed in this
    | array. Leave the array empty to allow all sources.
    |
    | This is useful for a little extra security if you run your own Git
    | repo server.
    |
    | Relies on the REMOTE_ADDR of the connecting client matching a value
    | in the array below. So if using IPv6 on both the server and the
    | notifing git server, then make sure to add it to the array. If your git
    | server listens on IPv4 and IPv6 it would be safest to add both.
    |
    | e.g.
    | 
    | 'allowed_sources' => ['192.160.0.1', '::1'], 
    |
    */

    'allowed_sources' => [],

    /*
    |--------------------------------------------------------------------------
    | Before Pull
    |--------------------------------------------------------------------------
    |
    | Execute list command before pull as www-data
    |
    | Example:
    | 'before_pull' => ['php artisan down'],
    |
    */
    'before_pull' => [],

    /*
    |--------------------------------------------------------------------------
    | After Pull
    |--------------------------------------------------------------------------
    |
    | List of command will execute in last job as www-data
    |
    | Example:
    | 'before_pull' => ['composer install --no-dev', 'php artisan up'],
    |
    */
    'after_pull'  => [],

    /*
    |--------------------------------------------------------------------------
    | Secret signature
    |--------------------------------------------------------------------------
    |
    | Allow webhook requests to be signed with a secret signature.
    |
    | If 'secret' is set to true, Gitdeploy will deny requests where the
    | signature does not match. If set to false it will ignore any signature
    | headers it recieves.
    | 
    */

    'secret' => false,

    /**
     * The key you specified in the pushing client
     */
    'secret_key' => env('GIT_DEPLOY_KEY', ''),

];