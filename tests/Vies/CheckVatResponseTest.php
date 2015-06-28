<?php
namespace DragonBe\Test\Vies;

use DragonBe\Vies\CheckVatResponse;

class CheckVatResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param bool $isValid
     * @return \stdClass
     */
    protected function createViesResponse($isValid = true)
    {
        $response = new \stdClass();
        $response->countryCode = 'BE';
        $response->vatNumber = '123456749';
        $response->requestDate = date('Y-m-dP');
        $response->valid = $isValid;
        $response->traderName = 'Testing Corp N.V.';
        $response->traderAddress = 'MARKT 1' . PHP_EOL . '1000  BRUSSEL';
        $response->requestIdentifier = 'XYZ1234567890';
        return $response;
    }
    /**
     * @param bool $isValid
     * @return array
     */
    protected function createViesResponseArray($isValid = true)
    {
        return [
            'countryCode' => 'BE',
            'vatNumber'   => '123456749',
            'requestDate' => date('Y-m-dP'),
            'valid'       => $isValid,
            'traderName'        => 'Testing Corp N.V.',
            'traderAddress'     => 'MARKT 1' . PHP_EOL . '1000  BRUSSEL',
            'requestIdentifier' => 'XYZ1234567890'
        ];
    }

    public function validationProvider()
    {
        return array (
            array (true),
            array (false)
        );
    }

    /**
     * @dataProvider validationProvider
     */
    public function testCanCreateResponseAtConstruct($validCheck)
    {
        $response = $this->createViesResponse($validCheck);
        $checkVatResponse = new CheckVatResponse($response);
        $this->assertSame($response->countryCode, $checkVatResponse->getCountryCode());
        $this->assertSame($response->vatNumber, $checkVatResponse->getVatNumber());
        $this->assertSame($response->requestDate, $checkVatResponse->getRequestDate()->format(CheckVatResponse::VIES_DATETIME_FORMAT));
        $this->assertSame($response->valid, $checkVatResponse->isValid());
        $this->assertSame($response->traderName, $checkVatResponse->getName());
        $this->assertSame($response->traderAddress, $checkVatResponse->getAddress());
        $this->assertSame($response->requestIdentifier, $checkVatResponse->getIdentifier());
    }

    /**
     * @dataProvider validationProvider
     */
    public function testCanCreateResponseWithoutNameAndAddressAtConstruct($validCheck)
    {
        $response = $this->createViesResponse($validCheck);
        unset ($response->traderName, $response->traderAddress);
        $checkVatResponse = new CheckVatResponse($response);
        $this->assertSame($response->countryCode, $checkVatResponse->getCountryCode());
        $this->assertSame($response->vatNumber, $checkVatResponse->getVatNumber());
        $this->assertSame($response->requestDate, $checkVatResponse->getRequestDate()->format(CheckVatResponse::VIES_DATETIME_FORMAT));
        $this->assertSame($response->valid, $checkVatResponse->isValid());
        $this->assertSame($response->requestIdentifier, $checkVatResponse->getIdentifier());
        $this->assertSame('---', $checkVatResponse->getName());
        $this->assertSame('---', $checkVatResponse->getAddress());
    }

    /**
     * @dataProvider validationProvider
     */
    public function testCanCreateResponseWithArrayAtConstruct($validCheck)
    {
        $response = $this->createViesResponseArray($validCheck);
        $checkVatResponse = new CheckVatResponse($response);
        $this->assertSame($response['countryCode'], $checkVatResponse->getCountryCode());
        $this->assertSame($response['vatNumber'], $checkVatResponse->getVatNumber());
        $this->assertSame($response['requestDate'], $checkVatResponse->getRequestDate()->format(CheckVatResponse::VIES_DATETIME_FORMAT));
        $this->assertSame($response['valid'], $checkVatResponse->isValid());
        $this->assertSame($response['traderName'], $checkVatResponse->getName());
        $this->assertSame($response['traderAddress'], $checkVatResponse->getAddress());
        $this->assertSame($response['requestIdentifier'], $checkVatResponse->getIdentifier());
    }

    public function testDefaultDateIsNow()
    {
        $vatResponse = new CheckVatResponse();
        $this->assertInstanceOf('\\DateTime', $vatResponse->getRequestDate());
        $this->assertSame(date('Y-m-dP'), $vatResponse->getRequestDate()->format('Y-m-dP'));
    }
}
