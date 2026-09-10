<?php

declare(strict_types = 1);

namespace Ddeboer\Vatin\Exception;

class ServiceUnavailableException extends \RuntimeException implements ViesExceptionInterface
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('An error was encountered at the network or web application level', previous: $previous);
    }
}
