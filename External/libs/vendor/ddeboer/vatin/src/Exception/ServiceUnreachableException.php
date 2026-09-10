<?php

declare(strict_types = 1);

namespace Ddeboer\Vatin\Exception;

class ServiceUnreachableException extends \RuntimeException implements ViesExceptionInterface
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('VIES service unreachable', previous: $previous);
    }
}
