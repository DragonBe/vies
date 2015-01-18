<?php
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
namespace DragonBe\Vies;
/**
 * CheckVatResponse
 * 
 * This is the response object from the VIES web service for validation of
 * VAT numbers of companies registered in the European Union.
 * 
 * @see \DragonBe\Vies\Exception
 * @category DragonBe
 * @package \DragonBe\Vies
 */
class CheckVatResponse
{
    const VIES_DATETIME_FORMAT = 'Y-m-d+H:i';

    /**
     * @var string The country code for a member of the European Union
     */
    protected $countryCode;
    /**
     * @var string The VAT number of a registered European company
     */
    protected $vatNumber;
    /**
     * @var \DateTime The date of the request
     */
    protected $requestDate;
    /**
     * @var bool Flag indicating the VAT number is valid
     */
    protected $valid;
    /**
     * @var string The registered name of a validated company (optional)
     */
    protected $name;
    /**
     * @var string The registered address of a validated company (optional)
     */
    protected $address;

    /**
     * Constructor for this response object
     *
     * @param null|StdClass $params
     */
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
        $this->countryCode = (string) $countryCode;
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
        return $this->countryCode;
    }
    /**
     * Sets the VAT number of a company within the European Union
     * 
     * @param string $vatNumber
     * @return \DragonBe\Vies\CheckVatResponse
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = (string) $vatNumber;
        return $this;
    }
    /**
     * Retrieves the VAT number from a company within the European Union
     * 
     * @return string
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }
    /**
     * Sets the date- and timestamp when the VIES service response was created
     * 
     * @param string $requestDate
     * @return \DragonBe\Vies\CheckVatResponse
     */
    public function setRequestDate($requestDate)
    {
        if (!$requestDate instanceof \DateTime) {
            // converting Y-m-d+H:i into Y-m-d H:i:s
            $requestDate = str_replace('+', ' ', $requestDate) . ':00';
            $requestDate = new \DateTime($requestDate);
        }
        $this->requestDate = $requestDate;
        return $this;
    }
    /**
     * Retrieves the date- and timestamp the VIES service response was created
     * 
     * @return \DateTime
     */
    public function getRequestDate()
    {
        if (null === $this->requestDate) {
            $this->requestDate = new \DateTime();
        }
        return $this->requestDate;
    }
    /**
     * Sets the flag to indicate the provided details were valid or not
     * 
     * @param bool $flag
     * @return \DragonBe\Vies\CheckVatResponse
     */
    public function setValid($flag)
    {
        $this->valid = (boolean) $flag;
        return $this;
    }
    /**
     * Checks to see if a request is valid with given parameters
     * 
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }
    /**
     * Sets optionally the registered name of the company
     * 
     * @param string $name
     * @return \DragonBe\Vies\CheckVatResponse
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }
    /**
     * Retrieves the registered name of the company
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Sets the registered address of a company
     * 
     * @param string $address
     * @return \DragonBe\Vies\CheckVatResponse
     */
    public function setAddress($address)
    {
        $this->address = (string) $address;
        return $this;
    }
    /**
     * Retrieves the registered address of a company
     * 
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    /**
     * Populates this response object with external data
     * 
     * @param array|\SdtClass $row
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
        isset ($row->name) ?
            $this->setName($row->name) :
            $this->setName('---');

        isset ($row->address)
            ? $this->setAddress($row->address)
            : $this->setAddress('---');
    }
    public function toArray()
    {
        return array (
            'countryCode' => $this->getCountryCode(),
            'vatNumber'   => $this->getVatNumber(),
            'requestDate' => $this->getRequestDate()->format('Y-m-d'),
            'valid'       => $this->isValid(),
            'name'        => $this->getName(),
            'address'     => $this->getAddress(),
        );
    }
}
