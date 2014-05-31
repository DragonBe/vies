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
class Vies extends \Zend_Soap_Client
{
    const VIES_WSDL = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';
    const VIES_SOAP_VERSION = '1.1';

    /**
     * @param string $wsdl
     * @param null $options
     */
    public function __construct($wsdl = self::VIES_WSDL, $options = null)
    {
        parent::__construct($wsdl, $options);
        $this->setSoapVersion(SOAP_1_1);
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
        $response = $this->getSoapClient()->checkVat(array (
            'countryCode' => $countryCode, 'vatNumber' => $vatNumber));
        return new CheckVatResponse($response);
    }
    /**
     * Validates a company with additional information for more precise
     * lookup and validation.
     * 
     * @param string $countryCode
     * @param string $vatNumber
     * @param string $traderName
     * @param string $traderCompanyType
     * @param string $traderStreet
     * @param string $traderPostcode
     * @param string $traderCity
     * @param string $requesterCountryCode
     * @param string $requesterVatNumber
     * @return CheckVatApproxResponse
     */
    public function validateVatApprox($countryCode,$vatNumber,
        $traderName = null,$traderCompanyType = null,$traderStreet = null,
        $traderPostcode = null, $traderCity = null, 
        $requesterCountryCode = null,$requesterVatNumber = null)
    {
        $vatNumber = self::filterVat($vatNumber);
        $response = $this->_soapClient->checkVatApprox(array (
            'countryCode' => $countryCode,'vatNumber' => $vatNumber,
            'traderName' => $traderName,'traderCompanyType' => $traderCompanyType,
            'traderStreet' => $traderStreet,'traderPostcode' => $traderPostcode,
            'traderCity' => $traderCity,'requesterCountryCode' => $requesterCountryCode,
            'requesterVatNumber' => $requesterVatNumber));
        return new CheckVatApproxResponse($response);
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
