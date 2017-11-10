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
        ];
    }
}
