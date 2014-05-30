<?php
namespace DragonBe\Vies;

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
 * My_Service_Vies_CheckVatApproxResponse
 * 
 * This is the response object from the VIES web service for validation of
 * VAT numbers of companies registered in the European Union.
 * 
 * @see Zend_Exception
 * @category My
 * @package My_Service
 * @subpackage My_Service_Vies
 */
class CheckVatApproxResponse
{
    /**
     * @var string The country code for a member of the European Union
     */
    protected $_countryCode;
    /**
     * @var string The VAT number of a registered European company
     */
    protected $_vatNumber;
    /**
     * @var string The date of the request
     */
    protected $_requestDate;
    /**
     * @var bool Flag indicating the VAT number is valid
     */
    protected $_valid;
    /**
     * @var string The registered name of a validated company (optional)
     */
    protected $_traderName;
    /**
     * @var string The registered address of a validated company (optional)
     */
    protected $_traderAddress;
    /**
     * @var string The registered type of a company
     */
    protected $_traderCompanyType;
    /**
     * @var string The registerd street of a company
     */
    protected $_traderStreet;
    /**
     * @var string The registerd postal code of a company
     */
    protected $_traderPostcode;
    /**
     * @var string The registered city of a company
     */
    protected $_traderCity;
    /**
     * @var string Status message indicating provided information is correct
     */
    protected $_traderNameMatch;
    /**
     * @var string Status message indicating provided information is correct
     */
    protected $_traderCompanyTypeMatch;
    /**
     * @var string Status message indicating provided information is correct
     */
    protected $_traderStreetMatch;
    /**
     * @var string Status message indicating provided information is correct
     */
    protected $_traderPostcodeMatch;
    /**
     * @var string Status message indicating provided information is correct
     */
    protected $_traderCityMatch;
    /**
     * @var string The ID of this request
     */
    protected $_requestIdentifier;
    
    public function __construct($params = null)
    {
        if (null !== $params) {
            $this->populate($params);
        }
    }
    /**
     * Sets the two-character country code for a member of the European Union
     *   
     * @param string $countryCode
     * @return My_Service_Vies_CheckVatApproxResponse
     */
    public function setCountryCode($countryCode)
    {
        $this->_countryCode = (string) $countryCode;
        return $this;
    }
    /**
     * Retrieves the two-character country code from a member of the European
     * Union.
     * 
     * @return string
     */
    public function getCountryCode()
    {
        return $this->_countryCode;
    }
    /**
     * Sets the VAT number of a company within the European Union
     * 
     * @param string $vatNumber
     * @return My_Service_Vies_CheckVatApproxResponse
     */
    public function setVatNumber($vatNumber)
    {
        $this->_vatNumber = (string) $vatNumber;
        return $this;
    }
    /**
     * Retrieves the VAT number from a company within the European Union
     * 
     * @return string
     */
    public function getVatNumber()
    {
        return $this->_vatNumber;
    }
    /**
     * Sets the date- and timestamp when the VIES service response was created
     * 
     * @param string $requestDate
     * @return My_Service_Vies_CheckVatApproxResponse
     */
    public function setRequestDate($requestDate)
    {
        $this->_requestDate = (string) $requestDate;
        return $this;
    }
    /**
     * Retrieves the date- and timestamp the VIES service response was created
     * 
     * @return string
     */
    public function getRequestDate()
    {
        return $this->_requestDate;
    }
    /**
     * Sets the flag to indicate the provided details were valid or not
     * 
     * @param bool $flag
     * @return My_Service_Vies_CheckVatApproxResponse
     */
    public function setValid($flag)
    {
        $this->_valid = (boolean) $flag;
        return $this;
    }
    /**
     * Checks to see if a request is valid with given parameters
     * 
     * @return bool
     */
    public function isValid()
    {
        return $this->_valid;
    }
    /**
     * Sets optionally the registered name of the company
     * 
     * @param string $traderName
     * @return My_Service_Vies_CheckVatApproxResponse
     */
    public function setTraderName($traderName)
    {
        $this->_traderName = (string) $traderName;
        return $this;
    }
    /**
     * Retrieves the registered name of the company
     * 
     * @return string
     */
    public function getTraderName()
    {
        return $this->_traderName;
    }
    public function setTraderCompanyType($traderCompanyType)
    {
        $this->_traderCompanyType = (string) $traderCompanyType;
        return $this;
    }
    public function getTraderCompanyType()
    {
        return $this->_traderCompanyType;
    }
    public function setTraderStreet($traderStreet)
    {
        $this->_traderStreet = (string) $traderStreet;
        return $this;
    }
    public function getTraderStreet()
    {
        return $this->_traderStreet;
    }
    public function setTraderPostcode($traderPostcode)
    {
        $this->_traderPostcode = (string) $traderPostcode;
        return $this;
    }
    public function getTraderPostcode()
    {
        return $this->_traderPostcode;
    }
    public function setTraderCity($traderCity)
    {
        $this->_traderCity = (string) $traderCity;
        return $this;
    }
    public function getTraderCity()
    {
        return $this->_traderCity;
    }
    public function setTraderNameMatch($match)
    {
        $this->_traderNameMatch = (string) $match;
        return $this;
    }
    public function getTraderNameMatch()
    {
        return $this->_traderNameMatch;
    }
    public function setTraderCompanyTypeMatch($match)
    {
        $this->_traderCompanyTypeMatch = (string) $match;
        return $this;
    }
    public function getTraderCompanyTypeMatch()
    {
        return $this->_traderCompanyTypeMatch;
    }
    public function setTraderStreetMatch($match)
    {
        $this->_traderStreetMatch = (string) $match;
        return $this;
    }
    public function getTraderStreetMatch()
    {
        return $this->_traderStreetMatch;
    }
    public function setTraderPostcodeMatch($match)
    {
        $this->_traderPostcodeMatch = (string) $match;
        return $this;
    }
    public function getTraderPostcodeMatch()
    {
        return $this->_traderPostcodeMatch;
    }
    public function setTraderCityMatch($match)
    {
        $this->_traderCityMatch = (string) $match;
        return $this;
    }
    public function getTraderCityMatch()
    {
        return $this->_traderCityMatch;
    }
    public function setRequestIdentifier($requestIdentifier)
    {
        $this->_requestIdentifier = (string) $requestIdentifier;
        return $this;
    }
    public function getRequestIdentifier()
    {
        return $this->_requestIdentifier;
    }
    /**
     * Populates this response object with external data
     * 
     * @param array|Zend_Db_Table $row
     */
    public function populate($row)
    {
        if (is_array($row)) {
            $row = new \ArrayObject($row, \ArrayObject::ARRAY_AS_PROPS);
        }
        // required parameters
        $this->setCountryCode($row->countryCode)
             ->setVatNumber($row->vatNumber)
             ->setRequestDate($row->requestDate)
             ->setValid($row->valid);
             
        // optional parameters
        $this->_populateOpt($row, 'traderName')
             ->_populateOpt($row, 'traderCompanyType')
             ->_populateOpt($row, 'traderStreet')
             ->_populateOpt($row, 'traderPostcode')
             ->_populateOpt($row, 'traderCity')
             ->_populateOpt($row, 'traderNameMatch')
             ->_populateOpt($row, 'traderCompanyTypeMatch')
             ->_populateOpt($row, 'traderStreetMatch')
             ->_populateOpt($row, 'traderPostcodeMatch')
             ->_populateOpt($row, 'traderCityMatch')
             ->_populateOpt($row, 'requestIdentifier');
    }
    private function _populateOpt($row, $key)
    {
        $method = 'set' . ucfirst($key);
        if (isset ($row->$key)) {
            $this->$method($row->$key);
        }
        return $this;
    }
    public function toArray()
    {
        return array (
            'countryCode' => $this->getCountryCode(),
            'vatNumber' => $this->getVatNumber(),
            'requestDate' => $this->getRequestDate(),
            'valid' => $this->isValid(),
            'traderName' => $this->getTraderName(),
            'traderCompanyType' => $this->getTraderCompanyType(),
            'traderStreet' => $this->getTraderStreet(),
            'traderPostcode' => $this->getTraderPostcode(),
            'traderCity' => $this->getTraderCity(),
            'traderNameMatch' => $this->getTraderNameMatch(),
            'traderCompanyTypeMatch' => $this->getTraderCompanyTypeMatch(),
            'traderStreetMatch' => $this->getTraderStreetMatch(),
            'traderPostcodeMatch' => $this->getTraderPostcodeMatch(),
            'traderCityMatch' => $this->getTraderCityMatch(),
            'requestIdentifier' => $this->getRequestIdentifier(),
        );
    }
}
