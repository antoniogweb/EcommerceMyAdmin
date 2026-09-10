<?php

declare(strict_types = 1);

namespace Ddeboer\Vatin\Exception;

class StateServiceUnavailableException extends \RuntimeException implements ViesExceptionInterface
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('The application at the member state is not replying or not available', previous: $previous);
    }
}
