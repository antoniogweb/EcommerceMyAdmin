<?php

declare(strict_types = 1);

namespace Ddeboer\Vatin\Vies;

use Ddeboer\Vatin\Exception\ServiceUnreachableException;
use Ddeboer\Vatin\Exception\ViesException;

/**
 * A client for the VIES SOAP web service
 */
interface ClientInterface
{
    /**
     * Check VAT
     *
     * @param string $countryCode Country code
     * @param string $vatNumber   VAT number
     *
     * @throws ServiceUnreachableException
     * @throws ViesException
     */
    public function checkVat(string $countryCode, string $vatNumber): Response\CheckVatResponse;
}
