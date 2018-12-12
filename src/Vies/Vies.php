<?php

declare (strict_types=1);

/**
 * Vies
 *
 * Component using the European Commission (EC) VAT Information Exchange System (VIES) to verify and validate VAT
 * registration numbers in the EU, using PHP and Composer.
 *
 * @author  Michelangelo van Dam <dragonbe+github@gmail.com>
 * @license  MIT
 *
 */
namespace DragonBe\Vies;

/**
 * Thrown under certain circumstances by SoapClient, when exchanging data with European Commission (EC) VAT
 * Information Exchange System (VIES).
 *
 * Common SoapFaults include:
 *
 * MS_UNAVAILABLE            : The Member State service is unavailable. Try again later or with another Member State.
 * SERVER_BUSY               : The service can not process your request. Try again later.
 * SERVICE_UNAVAILABLE       : The SOAP service is unavailable, try again later.
 * TIMEOUT                   : The Member State service could not be reach in time, try again later or with another
 *                             Member State
 *
 * GLOBAL_MAX_CONCURRENT_REQ : The number of concurrent requests is more than the VIES service allows.
 * MS_MAX_CONCURRENT_REQ     : Same as MS_MAX_CONCURRENT_REQ.
 */
use DragonBe\Vies\Validator;
use SoapClient;
use SoapFault;

/**
 * Class Vies
 *
 * This class provides a soap client for usage of the VIES web service
 * provided by the European Commission to validate VAT numbers of companies
 * registered within the European Union
 *
 * @category DragonBe
 * @package \DragonBe\Vies
 * @link http://ec.europa.eu/taxation_customs/vies/faqvies.do#item16
 */
class Vies
{
    const VIES_PROTO = 'http';
    const VIES_DOMAIN = 'ec.europa.eu';
    const VIES_WSDL = '/taxation_customs/vies/checkVatService.wsdl';
    const VIES_EU_COUNTRY_TOTAL = 28;

    protected const VIES_EU_COUNTRY_LIST = [
        'AT' => ['name' => 'Austria', 'validator' => Validator\ValidatorAT::class],
        'BE' => ['name' => 'Belgium', 'validator' => Validator\ValidatorBE::class],
        'BG' => ['name' => 'Bulgaria', 'validator' => Validator\ValidatorBG::class],
        'CY' => ['name' => 'Cyprus', 'validator' => Validator\ValidatorCY::class],
        'CZ' => ['name' => 'Czech Republic', 'validator' => Validator\ValidatorCZ::class],
        'DE' => ['name' => 'Germany', 'validator' => Validator\ValidatorDE::class],
        'DK' => ['name' => 'Denmark', 'validator' => Validator\ValidatorDK::class],
        'EE' => ['name' => 'Estonia', 'validator' => Validator\ValidatorEE::class],
        'EL' => ['name' => 'Greece', 'validator' => Validator\ValidatorEL::class],
        'ES' => ['name' => 'Spain', 'validator' => Validator\ValidatorES::class],
        'FI' => ['name' => 'Finland', 'validator' => Validator\ValidatorFI::class],
        'FR' => ['name' => 'France', 'validator' => Validator\ValidatorFR::class],
        'HR' => ['name' => 'Croatia', 'validator' => Validator\ValidatorHR::class],
        'HU' => ['name' => 'Hungary', 'validator' => Validator\ValidatorHU::class],
        'IE' => ['name' => 'Ireland', 'validator' => Validator\ValidatorIE::class],
        'IT' => ['name' => 'Italy', 'validator' => Validator\ValidatorIT::class],
        'LU' => ['name' => 'Luxembourg', 'validator' => Validator\ValidatorLU::class],
        'LV' => ['name' => 'Latvia', 'validator' => Validator\ValidatorLV::class],
        'LT' => ['name' => 'Lithuania', 'validator' => Validator\ValidatorLT::class],
        'MT' => ['name' => 'Malta', 'validator' => Validator\ValidatorMT::class],
        'NL' => ['name' => 'Netherlands', 'validator' => Validator\ValidatorNL::class],
        'PL' => ['name' => 'Poland', 'validator' => Validator\ValidatorPL::class],
        'PT' => ['name' => 'Portugal', 'validator' => Validator\ValidatorPT::class],
        'RO' => ['name' => 'Romania', 'validator' => Validator\ValidatorRO::class],
        'SE' => ['name' => 'Sweden', 'validator' => Validator\ValidatorSE::class],
        'SI' => ['name' => 'Slovenia', 'validator' => Validator\ValidatorSI::class],
        'SK' => ['name' => 'Slovakia', 'validator' => Validator\ValidatorSK::class],
        'GB' => ['name' => 'United Kingdom', 'validator' => Validator\ValidatorGB::class],
    ];

    /**
     * @var SoapClient
     */
    protected $soapClient;

    /**
     * @var string The WSDL for VIES service
     */
    protected $wsdl;

    /**
     * @var array Options for the SOAP client
     */
    protected $options;

    /**
     * @var HeartBeat A heartbeat checker to verify if the VIES service is available
     */
    protected $heartBeat;

    /**
     * Retrieves the SOAP client that will be used to communicate with the VIES
     * SOAP service.
     *
     * @return SoapClient
     */
    public function getSoapClient(): SoapClient
    {
        $this->soapClient = $this->soapClient ?? new SoapClient($this->getWsdl(), $this->getOptions());

        return $this->soapClient;
    }

    /**
     * Sets the PHP SOAP Client and allows you to override the use of the native
     * PHP SoapClient for testing purposes or for better integration in your own
     * application.
     *
     * @param SoapClient $soapClient
     * @return self
     */
    public function setSoapClient(SoapClient $soapClient): self
    {
        $this->soapClient = $soapClient;

        return $this;
    }

    /**
     * Retrieves the location of the WSDL for the VIES SOAP service
     *
     * @return string
     */
    public function getWsdl(): string
    {
        $this->wsdl = $this->wsdl ?? sprintf('%s://%s%s', self::VIES_PROTO, self::VIES_DOMAIN, self::VIES_WSDL);

        return $this->wsdl;
    }

    /**
     * Sets the location of the WSDL for the VIES SOAP Service
     *
     * @param string $wsdl
     *
     * @return self
     *
     * @example http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl
     */
    public function setWsdl(string $wsdl): self
    {
        $this->wsdl = $wsdl;

        return $this;
    }

    /**
     * Retrieves the options for the PHP SOAP service
     *
     * @return array
     */
    public function getOptions(): array
    {
        $this->options = $this->options ?? [];

        return $this->options;
    }

    /**
     * Set options for the native PHP Soap Client
     *
     * @param array $options
     * @return self
     * @link http://php.net/manual/en/soapclient.soapclient.php
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Retrieves the heartbeat class that offers the option to check if the VIES
     * service is up-and-running.
     *
     * @return HeartBeat
     */
    public function getHeartBeat(): HeartBeat
    {
        $this->heartBeat = $this->heartBeat ?? new HeartBeat('tcp://' . self::VIES_DOMAIN, 80);

        return $this->heartBeat;
    }

    /**
     * Sets the heartbeat functionality to verify if the VIES service is alive or not,
     * especially since this service tends to have a bad reputation of its availability.
     *
     * @param HeartBeat $heartBeat
     * @return self
     */
    public function setHeartBeat(HeartBeat $heartBeat): self
    {
        $this->heartBeat = $heartBeat;

        return $this;
    }

    /**
     * Validates a given country code and VAT number and returns a
     * \DragonBe\Vies\CheckVatResponse object
     *
     * @param string $countryCode The two-character country code of a European
     * member country
     * @param string $vatNumber The VAT number (without the country
     * identification) of a registered company
     * @param string $requesterCountryCode The two-character country code of a European
     * member country
     * @param string $requesterVatNumber The VAT number (without the country
     * identification) of a registered company
     * @param string $traderName The name of the company you want to validate
     * @param string $traderCompanyType The type of company you want to validate
     * @param string $traderStreet The street of the company you want to validate
     * @param string $traderPostcode The postal code of the company you want to validate
     * @param string $traderCity The city of the company you want to validate
     * @return CheckVatResponse
     * @throws ViesException
     * @throws ViesServiceException
     */
    public function validateVat(
        string $countryCode,
        string $vatNumber,
        string $requesterCountryCode = '',
        string $requesterVatNumber = '',
        string $traderName = '',
        string $traderCompanyType = '',
        string $traderStreet = '',
        string $traderPostcode = '',
        string $traderCity = ''
    ): CheckVatResponse {

        if (! isset(self::VIES_EU_COUNTRY_LIST[$countryCode])) {
            throw new ViesException(sprintf('Invalid country code "%s" provided', $countryCode));
        }
        $vatNumber = self::filterVat($vatNumber);

        if (! $this->validateVatSum($countryCode, $vatNumber)) {
            $params = (object) [
                'countryCode' => $countryCode,
                'vatNumber' => $vatNumber,
                'requestDate' => date_create(),
                'valid' => false,
            ];

            return new CheckVatResponse($params);
        }

        $requestParams = [
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
        ];

        $this->addOptionalArguments($requestParams, 'traderName', $traderName);
        $this->addOptionalArguments($requestParams, 'traderCompanyType', $traderCompanyType);
        $this->addOptionalArguments($requestParams, 'traderStreet', $traderStreet);
        $this->addOptionalArguments($requestParams, 'traderPostcode', $traderPostcode);
        $this->addOptionalArguments($requestParams, 'traderCity', $traderCity);

        if ($requesterCountryCode && $requesterVatNumber) {
            if (! isset(self::VIES_EU_COUNTRY_LIST[$requesterCountryCode])) {
                throw new ViesException(sprintf('Invalid requestor country code "%s" provided', $requesterCountryCode));
            }
            $requesterVatNumber = self::filterVat($requesterVatNumber);

            $requestParams['requesterCountryCode'] = $requesterCountryCode;
            $requestParams['requesterVatNumber'] = $requesterVatNumber;
        }

        try {
            $response = $this->getSoapClient()->__soapCall('checkVatApprox', [$requestParams]);
            // Soap returns "yyyy-mm-dd+hh:mm" so we need to convert it
            $response->requestDate = date_create_from_format('Y-m-d\+H:i', $response->requestDate);
            return new CheckVatResponse($response);
        } catch (SoapFault $e) {
            $message = sprintf(
                'Back-end VIES service cannot validate the VAT number "%s%s" at this moment. '
                . 'The service responded with the critical error "%s". This is probably a temporary '
                . 'problem. Please try again later.',
                $countryCode,
                $vatNumber,
                $e->getMessage()
            );

            throw new ViesServiceException($message, 0, $e);
        }
    }

    /**
     * Validate a VAT number control sum
     *
     * @param string $countryCode The two-character country code of a European
     * member country
     * @param string $vatNumber The VAT number (without the country
     * identification) of a registered company
     * @return bool
     * @throws ViesException
     */
    public function validateVatSum(string $countryCode, string $vatNumber): bool
    {
        if (! isset(self::VIES_EU_COUNTRY_LIST[$countryCode])) {
            throw new ViesException(sprintf('Invalid country code "%s" provided', $countryCode));
        }
        $className = self::VIES_EU_COUNTRY_LIST[$countryCode]['validator'];

        return (new $className())->validate(self::filterVat($vatNumber));
    }

    /**
     * Filters a VAT number and normalizes it to an alfanumeric string
     *
     * @param string $vatNumber
     * @return string
     * @static
     */
    public static function filterVat(string $vatNumber): string
    {
        return str_replace([' ', '.', '-'], '', $vatNumber);
    }

    /**
     * A list of European Union countries as of January 2015
     *
     * @return array
     */
    public static function listEuropeanCountries(): array
    {
        static $list;

        $list = $list ?? array_combine(
            array_keys(self::VIES_EU_COUNTRY_LIST),
            array_column(self::VIES_EU_COUNTRY_LIST, 'name')
        );

        return $list;
    }

    /**
     * Here you can safely add optional arguments for verification
     *
     * @param array $requestParams
     * @param string $argumentKey
     * @param string $argumentValue
     * @return bool
     */
    private function addOptionalArguments(array &$requestParams, string $argumentKey, string $argumentValue): bool
    {
        if ('' !== $argumentValue) {
            $argumentValue = $this->filterArgument($argumentValue);
            if (! $this->validateArgument($argumentValue)) {
                throw new \InvalidArgumentException('The provided argument is not valid');
            }
            $requestParams[$argumentKey] = $argumentValue;
            return true;
        }
        return false;
    }

    /**
     * Filter the data so it's clean to be validated before sending
     * to the VIES service
     *
     * @param string $argumentValue
     * @return string
     */
    private function filterArgument(string $argumentValue): string
    {
        $argumentValue = filter_var($argumentValue, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);
        return $argumentValue;
    }

    /**
     * Validate the data to prevent XSS and other nasty things
     * from happening at the VIES service
     *
     * @param string $argumentValue
     * @return bool
     */
    private function validateArgument(string $argumentValue): bool
    {
        if (false === ($result = filter_var($argumentValue, FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '/^[a-zA-Z0-9\s\.\-,]+$/']
        ]))) {
            return false;
        }
        return true;
    }
}
