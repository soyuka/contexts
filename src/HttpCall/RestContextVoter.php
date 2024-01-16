<?php

namespace Behatch\HttpCall;

class RestContextVoter implements ContextSupportedVoter, FilterableHttpCallResult
{
    public function vote(HttpCallResult $httpCallResult)
    {
        return $httpCallResult->getValue() instanceof \Behat\Mink\Element\DocumentElement || is_string($httpCallResult->getValue());
    }

    public function filter(HttpCallResult $httpCallResult)
    {
        return is_string($httpCallResult->getValue()) ? $httpCallResult->getValue() : $httpCallResult->getValue()->getContent();
    }
}
