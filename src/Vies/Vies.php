<?php
namespace DragonBe\Vies;
/**
 * \DragonBe\Vies
 *
 * Component using the European Commission (EC) VAT Information Exchange System (VIES) to verify and validate VAT
 * registration numbers in the EU, using PHP and Composer.
 *
 * @author Michelangelo van Dam <dragonbe+github@gmail.com>
 * @license MIT
 *
 */
/**
 * Vies
 * 
 * This class provides a soap client for usage of the VIES web service
 * provided by the European Commission to validate VAT numbers of companies
 * registered within the European Union
 * 
 * @see \Zend_Soap_Client
 * @category DragonBe
 * @package \DragonBe\Vies
 * @link http://ec.europa.eu/taxation_customs/vies/faqvies.do#item16
 */
class Vies
{
    const VIES_WSDL = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

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
     * @return \SoapClient
     */
    public function getSoapClient()
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
     * @param \SoapClient $soapClient
     * @return Vies
     */
    public function setSoapClient($soapClient)
    {
        $this->soapClient = $soapClient;
        return $this;
    }

    /**
     * @return string
     */
    public function getWsdl()
    {
        if (null === $this->wsdl) {
            $this->wsdl = self::VIES_WSDL;
        }
        return $this->wsdl;
    }

    /**
     * @param string $wsdl
     * @return Vies
     */
    public function setWsdl($wsdl)
    {
        $this->wsdl = $wsdl;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
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
    public function setOptions($options)
    {
        $this->options = $options;
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
     * @return \DragonBe\Vies\CheckVatResponse
     */
    public function validateVat($countryCode,$vatNumber)
    {
        $vatNumber = self::filterVat($vatNumber);
        $response = $this->getSoapClient()->__soapCall(
            'checkVat',
            array (
                array (
                    'countryCode' => $countryCode,
                    'vatNumber' => $vatNumber
                )
            )
        );
        return new CheckVatResponse($response);
    }
    /**
     * Filters a VAT number and normalizes it to an alfanumeric string
     * 
     * @param string $vatNumber
     * @return string
     * @static
     */
    public static function filterVat($vatNumber)
    {
        return str_replace(array (' ', '.', '-'), '', $vatNumber);
    }
}
