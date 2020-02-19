<?php

namespace Boytunghc\LaravelGitHook\Controllers\Hook;

class Github extends Hook
{
    protected $secretHeaderKey = 'X-Hub-Signature';

    public function verifySecret()
    {
        $requestKey = $this->request->header($this->secretHeaderKey);
        $hmac = hash_hmac('sha1', $this->request->getContent(), $this->secretKey);

        return hash_equals($requestKey, "sha1=$hmac") ? true : false;
    }
}
