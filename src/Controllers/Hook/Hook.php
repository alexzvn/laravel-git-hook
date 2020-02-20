<?php

namespace Boytunghc\LaravelGitHook\Controllers\Hook;

use Illuminate\Http\Request;
use Boytunghc\LaravelGitHook\Contracts\HookContract;

abstract class Hook implements HookContract
{
    /**
     * Request
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Secret key deploy
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Body payload from request
     *
     * @var mixed json decoded object
     */
    public $payload;

    /**
     * Header key
     * 
     * @var string
     */
    protected $secretHeaderKey;

    public function __construct(Request $request) {
        $this->request   = $request;
        $this->secretKey = config('githook.secret_key');
        $this->payload   = $this->payload();
    }

    public function branch()
    {
        if (empty($this->payload)) {
            return '';
        }

        return explode('/', $this->payload->ref)[2];
    }

    public function commits()
    {
        return $this->payload->commits;
    }

    /**
     * Decode json
     *
     * @return mixed
     */
    protected function payload()
    {
        $payload = json_decode($this->request->getContent());

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $payload;
    }
}
