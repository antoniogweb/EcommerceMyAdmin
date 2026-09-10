<?php

namespace Ddeboer\Vatin\Exception;

class ViesException extends \RuntimeException implements ViesExceptionInterface
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('Error communicating with VIES service', previous: $previous);
    }
}
