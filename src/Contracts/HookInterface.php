<?php

namespace Boytunghc\LaravelGitHook\Contracts;

use Illuminate\Http\Request;

interface HookInterface
{
    /**
     * Body payload from request
     *
     * @var mixed json decoded object
     */
    public $payload;

    /**
     * bind dependency
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request);

    /**
     * Verify secret key from header
     *
     * @return boolean
     */
    public function verifySecret();

    /**
     * Get commits from push
     *
     * @return mixed[]
     */
    public function commits();

    /**
     * Get branch name
     *
     * @return string
     */
    public function branch();
}