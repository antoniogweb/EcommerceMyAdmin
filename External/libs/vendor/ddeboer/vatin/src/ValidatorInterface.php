<?php

declare(strict_types = 1);

namespace Ddeboer\Vatin;

interface ValidatorInterface
{
    /**
     * Returns true if value is a valid VAT identification number, false
     * otherwise.
     *
     * @param bool $checkExistence In addition to checking the VATIN's format
     *                             for validity, also check whether the VATIN
     *                             exists. This requires a call to the VIES web
     *                             service.
     */
    public function isValid(?string $value, bool $checkExistence = false): bool;
}
