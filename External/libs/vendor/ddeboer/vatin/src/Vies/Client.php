<?php

namespace Ddeboer\Vatin\Vies;

use Ddeboer\Vatin\Exception\InvalidInputException;
use Ddeboer\Vatin\Exception\MaxConcurrentRequestsException;
use Ddeboer\Vatin\Exception\MaxConcurrentStateRequestsException;
use Ddeboer\Vatin\Exception\ServiceUnavailableException;
use Ddeboer\Vatin\Exception\ServiceUnreachableException;
use Ddeboer\Vatin\Exception\StateServiceUnavailableException;
use Ddeboer\Vatin\Exception\TimeoutException;
use Ddeboer\Vatin\Exception\ViesException;
use Ddeboer\Vatin\Exception\ViesExceptionInterface;
use Ddeboer\Vatin\Vies\Response\CheckVatResponse;

/**
 * Default client for the VIES SOAP web service
 */
final class Client implements ClientInterface
{
    private const DEFAULT_WSDL = 'https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    /**
     * SOAP classmap
     *
     * @var array<string, class-string>
     */
    private const CLASS_MAP = [
        'checkVatResponse' => CheckVatResponse::class,
    ];

    /**
     * SOAP client
     */
    private ?\SoapClient $soapClient = null;

    /**
     * Constructor
     *
     * @param string $wsdl URL to WSDL
     */
    public function __construct(
        private readonly string $wsdl = self::DEFAULT_WSDL
    ) {
    }

    public function checkVat(string $countryCode, string $vatNumber): Response\CheckVatResponse
    {
        try {
            // silenced error because exceptions are thrown according to the client configuration
            $client = @$this->getSoapClient();

            return $client->checkVat(
                [
                    'countryCode' => $countryCode,
                    'vatNumber' => $vatNumber
                ]
            );
        } catch (\SoapFault $e) {
            throw $this->buildExceptionFromSoapFault($e);
        }
    }

    /**
     * @return \SoapClient a single instance of the local soap client
     *
     * @throws \SoapFault
     */
    private function getSoapClient(): \SoapClient
    {
        if (null === $this->soapClient) {
            $this->soapClient = new \SoapClient(
                $this->wsdl,
                [
                    'classmap' => self::CLASS_MAP,
                    'user_agent' => 'Mozilla', // the request fails unless a (dummy) user agent is specified
                    'exceptions' => true,
                ]
            );
        }

        return $this->soapClient;
    }

    private function buildExceptionFromSoapFault(\SoapFault $e): ViesExceptionInterface
    {
        if (str_contains($e->faultstring, 'failed to load')) {
            return new ServiceUnreachableException($e);
        }

        return match($e->faultstring) {
            'INVALID_INPUT' => new InvalidInputException($e),
            'GLOBAL_MAX_CONCURRENT_REQ' => new MaxConcurrentRequestsException($e),
            'MS_MAX_CONCURRENT_REQ' => new MaxConcurrentStateRequestsException($e),
            'SERVICE_UNAVAILABLE' => new ServiceUnavailableException($e),
            'MS_UNAVAILABLE' => new StateServiceUnavailableException($e),
            'TIMEOUT' => new TimeoutException($e),
            default => new ViesException($e),
        };
    }
}
