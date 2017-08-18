<?php
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
    public const VIES_PROTO = 'http';
    public const VIES_DOMAIN = 'ec.europa.eu';
    public const VIES_WSDL = '/taxation_customs/vies/checkVatService.wsdl';
    public const VIES_EU_COUNTRY_TOTAL = 28;

    /**
     * @var \SoapClient
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
     * @return \SoapClient
     */
    public function getSoapClient(): \SoapClient
    {
        if (null === $this->soapClient) {
            $this->soapClient = new \SoapClient(
                $this->getWsdl(),
                $this->getOptions()
            );
        }

        return $this->soapClient;
    }

    /**
     * Sets the PHP SOAP Client and allows you to override the use of the native
     * PHP SoapClient for testing purposes or for better integration in your own
     * application.
     *
     * @param \SoapClient $soapClient
     * @return Vies
     */
    public function setSoapClient(\SoapClient $soapClient): Vies
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
        if (null === $this->wsdl) {
            $this->wsdl = sprintf(
                '%s://%s%s',
                self::VIES_PROTO,
                self::VIES_DOMAIN,
                self::VIES_WSDL
            );
        }

        return $this->wsdl;
    }

    /**
     * Sets the location of the WSDL for the VIES SOAP Service
     *
     * @param string $wsdl
     * @return Vies
     * @example http://ec.europa.eu//taxation_customs/vies/checkVatService.wsdl
     */
    public function setWsdl(string $wsdl): Vies
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
        if (null === $this->options) {
            $this->options = [];
        }

        return $this->options;
    }

    /**
     * Set options for the native PHP Soap Client
     *
     * @param array $options
     * @return Vies
     * @link http://php.net/manual/en/soapclient.soapclient.php
     */
    public function setOptions(array $options): Vies
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
        if (null === $this->heartBeat) {
            $this->setHeartBeat(
                new HeartBeat(
                    'tcp://' . self::VIES_DOMAIN,
                    80
                )
            );
        }

        return $this->heartBeat;
    }

    /**
     * Sets the heartbeat functionality to verify if the VIES service is alive or not,
     * especially since this service tends to have a bad reputation of its availability.
     *
     * @param HeartBeat $heartBeat
     */
    public function setHeartBeat(HeartBeat $heartBeat)
    {
        $this->heartBeat = $heartBeat;
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
     * @return CheckVatResponse
     * @throws ViesException
     * @throws ViesServiceException
     */
    public function validateVat(
        string $countryCode,
        string $vatNumber,
        string $requesterCountryCode = '',
        string $requesterVatNumber = ''
    ) {

        if (! array_key_exists($countryCode, self::listEuropeanCountries())) {
            throw new ViesException(sprintf('Invalid country code "%s" provided', $countryCode));
        }
        $vatNumber = self::filterVat($vatNumber);

        if (! $this->validateVatSum($countryCode, $vatNumber)) {
            $params = new \StdClass();
            $params->countryCode = $countryCode;
            $params->vatNumber = $vatNumber;
            $params->requestDate = new \DateTime();
            $params->valid = false;

            return new CheckVatResponse($params);
        }

        $requestParams = [
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber
        ];

        if ($requesterCountryCode && $requesterVatNumber) {
            if (! array_key_exists($requesterCountryCode, self::listEuropeanCountries())) {
                throw new ViesException(
                    sprintf('Invalid requestor country code "%s" provided', $requesterCountryCode)
                );
            }
            $requesterVatNumber = self::filterVat($requesterVatNumber);

            $requestParams['requesterCountryCode'] = $requesterCountryCode;
            $requestParams['requesterVatNumber'] = $requesterVatNumber;
        }

        try {
            $response = $this->getSoapClient()->__soapCall(
                'checkVatApprox',
                [
                    $requestParams
                ]
            );
        } catch (SoapFault $e) {
            $message = sprintf('Back-end VIES service cannot validate the VAT number "%s%s" at this moment. '
                             . 'The service responded with the critical error "%s". This is probably a temporary '
                             . 'problem. Please try again later.',
                               $countryCode, $vatNumber, $e->getMessage());
            throw new ViesServiceException($message);
        }
        // Soap returns "yyyy-mm-dd+hh:mm" so we need to convert it
        $response->requestDate = new \DateTime(str_replace('+', ' ', $response->requestDate));

        return new CheckVatResponse($response);
    }

    /**
     * Validate a VAT number control sum
     *
     * @param string $countryCode The two-character country code of a European
     * member country
     * @param string $vatNumber The VAT number (without the country
     * identification) of a registered company
     * @return bool
     */
    public function validateVatSum(string $countryCode, string $vatNumber): bool
    {
        $className = __NAMESPACE__ . '\\Validator\\Validator' . $countryCode;
        /** @var Validator\ValidatorInterface $instance */
        $instance = new $className();

        $vatNumber = self::filterVat($vatNumber);
        return $instance->validate($vatNumber);
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
        return [
            'AT' => 'Austria',
            'BE' => 'Belgium',
            'BG' => 'Bulgaria',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DE' => 'Germany',
            'DK' => 'Denmark',
            'EE' => 'Estonia',
            'EL' => 'Greece',
            'ES' => 'Spain',
            'FI' => 'Finland',
            'FR' => 'France',
            'HR' => 'Croatia',
            'HU' => 'Hungary',
            'IE' => 'Ireland',
            'IT' => 'Italy',
            'LU' => 'Luxembourg',
            'LV' => 'Latvia',
            'LT' => 'Lithuania',
            'MT' => 'Malta',
            'NL' => 'Netherlands',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'RO' => 'Romania',
            'SE' => 'Sweden',
            'SI' => 'Slovenia',
            'SK' => 'Slovakia',
            'GB' => 'United Kingdom',
        ];
    }
}
