<?php

declare(strict_types = 1);

namespace Ddeboer\Vatin\Exception;

class MaxConcurrentRequestsException extends \RuntimeException implements ViesExceptionInterface
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('The maximum number of concurrent requests has been reached', previous: $previous);
    }
}
