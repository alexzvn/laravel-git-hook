<?php

namespace Boytunghc\LaravelGitHook\Controllers\Hook;

class Gitlab extends Hook
{
    protected $secretHeaderKey = 'X-Gitlab-Token';

    public function verifySecret()
    {
        $requestKey = $this->request->header($this->secretHeaderKey);

        return $requestKey === $this->secretKey ? true : false;
    }
}
