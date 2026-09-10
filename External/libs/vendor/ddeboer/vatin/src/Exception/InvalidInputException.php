<?php

declare(strict_types = 1);

namespace Ddeboer\Vatin\Exception;

class InvalidInputException extends \RuntimeException implements ViesExceptionInterface
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('The provided country code is invalid or the VAT number is empty', previous: $previous);
    }
}
