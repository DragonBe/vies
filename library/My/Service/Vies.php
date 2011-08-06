<?php
/**
 * My
 * 
 * This library is an extension for Zend Framework and provides essential
 * components for usage within a full Zend Framework application.
 * 
 * @author Michelangelo van Dam <dragonbe+github@gmail.com>
 * @license Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 * @link http://creativecommons.org/licenses/by-sa/3.0/
 *
 */
/**
 * My_Service_Vies
 * 
 * This class provides a soap client for usage of the VIES web service
 * provided by the European commision to validate VAT numbers of companies
 * registered within the European Union
 * 
 * @see Zend_Soap_Client
 * @category My
 * @package My_Service
 * @subpackage My_Service_Vies
 * @link http://ec.europa.eu/taxation_customs/vies/faqvies.do#item16
 */
class My_Service_Vies extends Zend_Soap_Client
{
    const VIES_WSDL = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';
    
    public function __construct($wsdl = self::VIES_WSDL, $options = null)
    {
        parent::__construct($wsdl, $options);
    }
    
    /**
     * Validates a given country code and VAT number and returns a
     * My_Service_Vies_CheckVatResponse object
     * 
     * @param string $countryCode The two-character country code of a European
     * member country
     * @param string $vatNumber The VAT number (without the country 
     * identification) of a registered company
     * @return My_Service_Vies_CheckVatResponse
     */
    public function validateVat($countryCode,$vatNumber)
    {
        $vatNumber = self::filterVat($vatNumber);
        $response = $this->_soapClient->checkVat(array (
            'countryCode' => $countryCode, 'vatNumber' => $vatNumber));
        return new My_Service_Vies_CheckVatResponse($response);
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
        return new My_Service_Vies_CheckVatApproxResponse($response);
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
