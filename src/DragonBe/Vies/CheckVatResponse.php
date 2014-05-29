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
 * My_Service_Vies_CheckVatResponse
 * 
 * This is the response object from the VIES web service for validation of
 * VAT numbers of companies registered in the European Union.
 * 
 * @see Zend_Exception
 * @category My
 * @package My_Service
 * @subpackage My_Service_Vies
 */
class CheckVatResponse
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
    protected $_name;
    /**
     * @var string The registered address of a validated company (optional)
     */
    protected $_address;
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
     * @return \DragonBe\Vies\CheckVatResponse
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
     * @return \DragonBe\Vies\CheckVatResponse
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
     * @return \DragonBe\Vies\CheckVatResponse
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
     * @return \DragonBe\Vies\CheckVatResponse
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
     * @param string $name
     * @return \DragonBe\Vies\CheckVatResponse
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }
    /**
     * Retrieves the registered name of the company
     * 
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    /**
     * Sets the registered address of a company
     * 
     * @param string $address
     * @return \DragonBe\Vies\CheckVatResponse
     */
    public function setAddress($address)
    {
        $this->_address = (string) $address;
        return $this;
    }
    /**
     * Retrieves the registered address of a company
     * 
     * @return string
     */
    public function getAddress()
    {
        return $this->_address;
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
        if (isset ($row->name)) {
            $this->setName($row->name);
        }
        if (isset ($row->address)) {
            $this->setAddress($row->address);
        }
    }
    public function toArray()
    {
        return array (
            'countryCode' => $this->getCountryCode(),
            'vatNumber'   => $this->getVatNumber(),
            'requestDate' => $this->getRequestDate(),
            'valid'       => $this->isValid(),
            'name'        => $this->getName(),
            'address'     => $this->getAddress(),
        );
    }
}
