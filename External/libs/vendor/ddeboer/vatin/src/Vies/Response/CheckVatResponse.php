<?php

namespace Ddeboer\Vatin\Vies\Response;

final class CheckVatResponse
{
    public function __construct(
        public readonly string $countryCode,
        public readonly string $vatNumber,
        public readonly \DateTimeImmutable $date,
        public readonly bool $valid,
        public readonly ?string $name,
        public readonly ?string $address,
    ) {
    }

    public function __set(string $name, mixed $value): void
    {
        if ('requestDate' === $name && is_string($value)) {
            $this->date = new \DateTimeImmutable($value);
        }
    }
}
