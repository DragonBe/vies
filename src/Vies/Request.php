<?php

namespace DragonBe\Vies;

class Request
{
    protected $params = [
        'countryCode' => null,
        'vatNumber' => null,
        'traderName' => null,
        'traderCompanyType' => null,
        'traderStreet' => null,
        'traderPostcode' => null,
        'traderCity' => null,
        'requesterCountryCode' => null,
        'requesterVatNumber' => null,
    ];

    public function toArray(): array
    {
        $out = [];
        foreach ($this->params as $k => $v) {
            if (null === $v) {
                continue;
            }

            $out[$k] = $v;
        }

        return $out;
    }

    /**
     * @return string
     */
    public function getCountryCode(): ?string
    {
        return $this->params['countryCode'];
    }

    /**
     * @param string $countryCode
     * @return Request
     */
    public function setCountryCode(string $countryCode)
    {
        $this->params['countryCode'] = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getVatNumber(): ?string
    {
        return $this->params['vatNumber'];
    }

    /**
     * @param string $vatNumber
     * @return Request
     */
    public function setVatNumber(string $vatNumber): Request
    {
        $vatNumber = Vies::filterVat($vatNumber);
        $this->params['vatNumber'] = $vatNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getTraderName(): ?string
    {
        return $this->params['traderName'];
    }

    /**
     * @param string $traderName
     * @return Request
     */
    public function setTraderName(string $traderName): Request
    {
        $this->params['traderName'] = $traderName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTraderCompanyType(): ?string
    {
        return $this->params['traderCompanyType'];
    }

    /**
     * @param string $traderCompanyType
     * @return Request
     */
    public function setTraderCompanyType(string $traderCompanyType): Request
    {
        $this->params['traderCompanyType'] = $traderCompanyType;
        return $this;
    }

    /**
     * @return string
     */
    public function getTraderStreet(): ?string
    {
        return $this->params['traderStreet'];
    }

    /**
     * @param string $traderStreet
     * @return Request
     */
    public function setTraderStreet(string $traderStreet): Request
    {
        $this->params['traderStreet'] = $traderStreet;
        return $this;
    }

    /**
     * @return string
     */
    public function getTraderPostcode(): ?string
    {
        return $this->params['traderPostcode'];
    }

    /**
     * @param string $traderPostcode
     * @return Request
     */
    public function setTraderPostcode(string $traderPostcode): Request
    {
        $this->params['traderPostcode'] = $traderPostcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getTraderCity(): ?string
    {
        return $this->params['traderCity'];
    }

    /**
     * @param string $traderCity
     * @return Request
     */
    public function setTraderCity(string $traderCity): Request
    {
        $this->params['traderCity'] = $traderCity;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequesterCountryCode(): ?string
    {
        return $this->params['requesterCountryCode'];
    }

    /**
     * @param string $requesterCountryCode
     * @return Request
     */
    public function setRequesterCountryCode(string $requesterCountryCode): Request
    {
        $this->params['requesterCountryCode'] = $requesterCountryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequesterVatNumber(): ?string
    {
        return $this->params['requesterVatNumber'];
    }

    /**
     * @param string $requesterVatNumber
     * @return Request
     */
    public function setRequesterVatNumber($requesterVatNumber): Request
    {
        $requesterVatNumber = Vies::filterVat($requesterVatNumber);
        $this->params['requesterVatNumber'] = $requesterVatNumber;
        return $this;
    }
}
