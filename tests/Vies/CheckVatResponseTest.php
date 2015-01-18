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
        $response->vatNumber = '123456789';
        $response->requestDate = date('Y-m-d+H:i');
        $response->valid = $isValid;
        $response->name = 'Testing Corp N.V.';
        $response->address = 'MARKT 1' . PHP_EOL . '1000  BRUSSEL';
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
            'vatNumber'   => '123456789',
            'requestDate' => date('Y-m-d+H:i'),
            'valid'       => $isValid,
            'name'        => 'Testing Corp N.V.',
            'address'     => 'MARKT 1' . PHP_EOL . '1000  BRUSSEL',
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
        $this->assertSame($response->name, $checkVatResponse->getName());
        $this->assertSame($response->address, $checkVatResponse->getAddress());
    }

    /**
     * @dataProvider validationProvider
     */
    public function testCanCreateResponseWithoutNameAndAddressAtConstruct($validCheck)
    {
        $response = $this->createViesResponse($validCheck);
        unset ($response->name, $response->address);
        $checkVatResponse = new CheckVatResponse($response);
        $this->assertSame($response->countryCode, $checkVatResponse->getCountryCode());
        $this->assertSame($response->vatNumber, $checkVatResponse->getVatNumber());
        $this->assertSame($response->requestDate, $checkVatResponse->getRequestDate()->format(CheckVatResponse::VIES_DATETIME_FORMAT));
        $this->assertSame($response->valid, $checkVatResponse->isValid());
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
        $this->assertSame($response['name'], $checkVatResponse->getName());
        $this->assertSame($response['address'], $checkVatResponse->getAddress());
    }

    public function testDefaultDateIsNow()
    {
        $vatResponse = new CheckVatResponse();
        $this->assertInstanceOf('\\DateTime', $vatResponse->getRequestDate());
        $this->assertSame(date('Y-m-d H:i'), $vatResponse->getRequestDate()->format('Y-m-d H:i'));
    }
}
