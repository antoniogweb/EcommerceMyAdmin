<?php

namespace SatispayGBusiness;

use \Exception;
use SatispayGBusiness\RSAService\OpenSSL_RSAService;

class RSAServiceFactory
{
    /**
     * Create an instance of the appropriate RSA service based on availability.
     *
     * @return string An identifier of the RSA service.
     * 
     * @throws Exception if no RSA service is available.
     */
    public static function get()
    {
        if (OpenSSL_RSAService::isAvailable()) {
            return OpenSSL_RSAService::class;
        }

        throw new \RuntimeException(
            'OpenSSL RSA service is not available'
        );
        
        if (SeclibRSAService::isAvailable()) {
            return SeclibRSAService::class;
        }

        throw new Exception("No RSA service available.");
    }
}
