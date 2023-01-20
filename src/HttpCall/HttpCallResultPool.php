<?php

namespace Behatch\HttpCall;

class HttpCallResultPool
{
    /**
     * @var HttpCallResult|null
     */
    private $result;

    public function store(HttpCallResult $result): void
    {
        $this->result = $result;
    }

    /**
     * @return HttpCallResult|null
     */
    public function getResult()
    {
        return $this->result;
    }
}
