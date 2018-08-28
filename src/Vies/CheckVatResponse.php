<?php

declare (strict_types=1);

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

use DateTime;
use InvalidArgumentException;
use stdClass;

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
    public const VALID = 1;
    public const INVALID = 2;
    public const NOT_PROCESSED = 3;

    public const VIES_DATETIME_FORMAT = 'Y-m-dP';

    /**
     * @var string The country code for a member of the European Union
     */
    protected $countryCode;
    /**
     * @var string The VAT number of a registered European company
     */
    protected $vatNumber;
    /**
     * @var DateTime The date of the request
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
     * @var string The request Identifier (optional)
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $street;
    /**
     * @var string
     */
    protected $postcode;
    /**
     * @var string
     */
    protected $city;
    /**
     * @var string
     */
    protected $companyType;
    /**
     * @var int
     */
    protected $nameMatch;
    /**
     * @var int
     */
    protected $companyTypeMatch;
    /**
     * @var int
     */
    protected $streetMatch;
    /**
     * @var int
     */
    protected $postcodeMatch;
    /**
     * @var int
     */
    protected $cityMatch;


    /**
     * Constructor for this response object
     *
     * @param null|array|stdClass $params
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
     *
     * @return self
     */
    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }
    /**
     * Retrieves the two-character country code from a member of the European
     * Union.
     *
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }
    /**
     * Sets the VAT number of a company within the European Union
     *
     * @param string $vatNumber
     *
     * @return self
     */
    public function setVatNumber(string $vatNumber): self
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }
    /**
     * Retrieves the VAT number from a company within the European Union
     *
     * @return string
     */
    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    /**
     * Sets the date- and timestamp when the VIES service response was created
     *
     * @param DateTime $requestDate
     *
     * @return self
     */
    public function setRequestDate(DateTime $requestDate): self
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    /**
     * Retrieves the date- and timestamp the VIES service response was created
     *
     * @return DateTime
     */
    public function getRequestDate(): DateTime
    {
        $this->requestDate = $this->requestDate ?? date_create();

        return $this->requestDate;
    }
    /**
     * Sets the flag to indicate the provided details were valid or not
     *
     * @param bool $flag
     *
     * @return self
     */
    public function setValid(bool $flag): self
    {
        $this->valid = $flag;

        return $this;
    }
    /**
     * Checks to see if a request is valid with given parameters
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }
    /**
     * Sets optionally the registered name of the company
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Retrieves the registered name of the company
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the registered address of a company
     *
     * @param string $address
     *
     * @return self
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Retrieves the registered address of a company
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Sets request Identifier
     *
     * @param string $identifier
     *
     * @return self
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * get requerst Identifier
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return CheckVatResponse
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     * @return CheckVatResponse
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return CheckVatResponse
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompanyType()
    {
        return $this->companyType;
    }

    /**
     * @param string $companyType
     * @return CheckVatResponse
     */
    public function setCompanyType($companyType)
    {
        $this->companyType = $companyType;
        return $this;
    }

    /**
     * @return int
     */
    public function getNameMatch()
    {
        return $this->nameMatch;
    }

    /**
     * @param int $nameMatch
     * @return CheckVatResponse
     */
    public function setNameMatch($nameMatch)
    {
        $this->nameMatch = $nameMatch;
        return $this;
    }

    /**
     * @return int
     */
    public function getCompanyTypeMatch()
    {
        return $this->companyTypeMatch;
    }

    /**
     * @param int $companyTypeMatch
     * @return CheckVatResponse
     */
    public function setCompanyTypeMatch($companyTypeMatch)
    {
        $this->companyTypeMatch = $companyTypeMatch;
        return $this;
    }

    /**
     * @return int
     */
    public function getStreetMatch()
    {
        return $this->streetMatch;
    }

    /**
     * @param int $streetMatch
     * @return CheckVatResponse
     */
    public function setStreetMatch($streetMatch)
    {
        $this->streetMatch = $streetMatch;
        return $this;
    }

    /**
     * @return int
     */
    public function getPostcodeMatch()
    {
        return $this->postcodeMatch;
    }

    /**
     * @param int $postcodeMatch
     * @return CheckVatResponse
     */
    public function setPostcodeMatch($postcodeMatch)
    {
        $this->postcodeMatch = $postcodeMatch;
        return $this;
    }

    /**
     * @return int
     */
    public function getCityMatch()
    {
        return $this->cityMatch;
    }

    /**
     * @param int $cityMatch
     * @return CheckVatResponse
     */
    public function setCityMatch($cityMatch)
    {
        $this->cityMatch = $cityMatch;
        return $this;
    }

    /**
     * Populates this response object with external data
     *
     * @param array|stdClass $row
     */
    public function populate($row): void
    {
        if (is_array($row)) {
            $row = (object) $row;
        }

        $requiredFields = ['countryCode', 'vatNumber', 'requestDate', 'valid'];
        foreach ($requiredFields as $requiredField) {
            if (! isset($row->$requiredField)) {
                throw new InvalidArgumentException('Required field "' . $requiredField . '" is missing');
            }
        }

        $this
            // required parameters
            ->setCountryCode($row->countryCode)
             ->setVatNumber($row->vatNumber)
             ->setRequestDate($row->requestDate)
             ->setValid($row->valid)
            // optional parameters
            ->setName($row->traderName ?? '---')
            ->setAddress($row->traderAddress ?? '---')
            ->setIdentifier($row->requestIdentifier ?? '')

            ->setStreet($row->traderStreet ?? '---')
            ->setPostcode($row->traderPostcode ?? '---')
            ->setCity($row->city ?? '---')
            ->setCompanyType($row->companyType ?? '---')
            ->setNameMatch($row->traderNameMatch ?? self::NOT_PROCESSED)
            ->setCompanyTypeMatch($row->traderCompanyTypeMatch ?? self::NOT_PROCESSED)
            ->setStreetMatch($row->traderStreetMatch ?? self::NOT_PROCESSED)
            ->setPostcodeMatch($row->traderPostcodeMatch ?? self::NOT_PROCESSED)
            ->setCityMatch($row->traderCityMatch ?? self::NOT_PROCESSED)
        ;
    }

    /**
     * Return this object as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return  [
            'countryCode' => $this->getCountryCode(),
            'vatNumber'   => $this->getVatNumber(),
            'requestDate' => $this->getRequestDate()->format('Y-m-d'),
            'valid'       => $this->isValid(),
            'name'        => $this->getName(),
            'address'     => $this->getAddress(),
            'identifier'  => $this->getIdentifier(),
            'street'      => $this->getStreet(),
            'postcode'    => $this->getPostcode(),
            'city'        => $this->getCity(),
            'companyType' => $this->getCompanyType(),

            'nameMatch'        => $this->getNameMatch(),
            'companyTypeMatch' => $this->getCompanyTypeMatch(),
            'streetMatch'      => $this->getStreetMatch(),
            'postcodeMatch'    => $this->getPostcodeMatch(),
            'cityMatch'        => $this->getCityMatch()
        ];
    }
}
