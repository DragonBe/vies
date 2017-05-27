<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use DragonBe\Vies\CheckVatResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class CheckVatResponseTest
 *
 * @package DragonBe\Test\Vies
 */
class CheckVatResponseTest extends TestCase
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
        $response->requestDate = new \DateTime(date('Y-m-dP'));
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
            'requestDate' => new \DateTime(date('Y-m-dP')),
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
     * Test that a VAT response can be created
     *
     * @dataProvider validationProvider
     * @covers \DragonBe\Vies\CheckVatResponse::__construct
     * @covers \DragonBe\Vies\CheckVatResponse::populate
     * @covers \DragonBe\Vies\CheckVatResponse::setCountryCode
     * @covers \DragonBe\Vies\CheckVatResponse::getCountryCode
     * @covers \DragonBe\Vies\CheckVatResponse::setVatNumber
     * @covers \DragonBe\Vies\CheckVatResponse::getVatNumber
     * @covers \DragonBe\Vies\CheckVatResponse::setRequestDate
     * @covers \DragonBe\Vies\CheckVatResponse::getRequestDate
     * @covers \DragonBe\Vies\CheckVatResponse::setValid
     * @covers \DragonBe\Vies\CheckVatResponse::isValid
     * @covers \DragonBe\Vies\CheckVatResponse::setName
     * @covers \DragonBe\Vies\CheckVatResponse::getName
     * @covers \DragonBe\Vies\CheckVatResponse::setAddress
     * @covers \DragonBe\Vies\CheckVatResponse::getAddress
     * @covers \DragonBe\Vies\CheckVatResponse::setIdentifier
     * @covers \DragonBe\Vies\CheckVatResponse::getIdentifier
     */
    public function testCanCreateResponseAtConstruct($validCheck)
    {
        $response = $this->createViesResponse($validCheck);
        $checkVatResponse = new CheckVatResponse($response);
        $this->assertSame($response->countryCode, $checkVatResponse->getCountryCode());
        $this->assertSame($response->vatNumber, $checkVatResponse->getVatNumber());
        $this->assertSame($response->requestDate, $checkVatResponse->getRequestDate());
        $this->assertSame($response->valid, $checkVatResponse->isValid());
        $this->assertSame($response->traderName, $checkVatResponse->getName());
        $this->assertSame($response->traderAddress, $checkVatResponse->getAddress());
        $this->assertSame($response->requestIdentifier, $checkVatResponse->getIdentifier());
    }

    /**
     * @dataProvider validationProvider
     * @covers \DragonBe\Vies\CheckVatResponse::__construct
     * @covers \DragonBe\Vies\CheckVatResponse::populate
     * @covers \DragonBe\Vies\CheckVatResponse::setCountryCode
     * @covers \DragonBe\Vies\CheckVatResponse::getCountryCode
     * @covers \DragonBe\Vies\CheckVatResponse::setVatNumber
     * @covers \DragonBe\Vies\CheckVatResponse::getVatNumber
     * @covers \DragonBe\Vies\CheckVatResponse::setRequestDate
     * @covers \DragonBe\Vies\CheckVatResponse::getRequestDate
     * @covers \DragonBe\Vies\CheckVatResponse::setValid
     * @covers \DragonBe\Vies\CheckVatResponse::isValid
     * @covers \DragonBe\Vies\CheckVatResponse::setName
     * @covers \DragonBe\Vies\CheckVatResponse::getName
     * @covers \DragonBe\Vies\CheckVatResponse::setAddress
     * @covers \DragonBe\Vies\CheckVatResponse::getAddress
     * @covers \DragonBe\Vies\CheckVatResponse::setIdentifier
     * @covers \DragonBe\Vies\CheckVatResponse::getIdentifier
     */
    public function testCanCreateResponseWithoutNameAndAddressAtConstruct($validCheck)
    {
        $response = $this->createViesResponse($validCheck);
        unset ($response->traderName, $response->traderAddress);
        $checkVatResponse = new CheckVatResponse($response);
        $this->assertSame($response->countryCode, $checkVatResponse->getCountryCode());
        $this->assertSame($response->vatNumber, $checkVatResponse->getVatNumber());
        $this->assertSame($response->requestDate, $checkVatResponse->getRequestDate());
        $this->assertSame($response->valid, $checkVatResponse->isValid());
        $this->assertSame($response->requestIdentifier, $checkVatResponse->getIdentifier());
        $this->assertSame('---', $checkVatResponse->getName());
        $this->assertSame('---', $checkVatResponse->getAddress());
    }

    /**
     * @dataProvider validationProvider
     * @covers \DragonBe\Vies\CheckVatResponse::__construct
     * @covers \DragonBe\Vies\CheckVatResponse::populate
     * @covers \DragonBe\Vies\CheckVatResponse::setCountryCode
     * @covers \DragonBe\Vies\CheckVatResponse::getCountryCode
     * @covers \DragonBe\Vies\CheckVatResponse::setVatNumber
     * @covers \DragonBe\Vies\CheckVatResponse::getVatNumber
     * @covers \DragonBe\Vies\CheckVatResponse::setRequestDate
     * @covers \DragonBe\Vies\CheckVatResponse::getRequestDate
     * @covers \DragonBe\Vies\CheckVatResponse::setValid
     * @covers \DragonBe\Vies\CheckVatResponse::isValid
     * @covers \DragonBe\Vies\CheckVatResponse::setName
     * @covers \DragonBe\Vies\CheckVatResponse::getName
     * @covers \DragonBe\Vies\CheckVatResponse::setAddress
     * @covers \DragonBe\Vies\CheckVatResponse::getAddress
     * @covers \DragonBe\Vies\CheckVatResponse::setIdentifier
     * @covers \DragonBe\Vies\CheckVatResponse::getIdentifier
     */
    public function testCanCreateResponseWithArrayAtConstruct($validCheck)
    {
        $response = $this->createViesResponseArray($validCheck);
        $checkVatResponse = new CheckVatResponse($response);
        $this->assertSame($response['countryCode'], $checkVatResponse->getCountryCode());
        $this->assertSame($response['vatNumber'], $checkVatResponse->getVatNumber());
        $this->assertSame($response['requestDate'], $checkVatResponse->getRequestDate());
        $this->assertSame($response['valid'], $checkVatResponse->isValid());
        $this->assertSame($response['traderName'], $checkVatResponse->getName());
        $this->assertSame($response['traderAddress'], $checkVatResponse->getAddress());
        $this->assertSame($response['requestIdentifier'], $checkVatResponse->getIdentifier());
    }

    /**
     * @covers \DragonBe\Vies\CheckVatResponse::__construct
     * @covers \DragonBe\Vies\CheckVatResponse::getRequestDate
     */
    public function testDefaultDateIsNow()
    {
        $vatResponse = new CheckVatResponse();
        $this->assertInstanceOf('\\DateTime', $vatResponse->getRequestDate());
        $this->assertSame(date('Y-m-dP'), $vatResponse->getRequestDate()->format('Y-m-dP'));
    }

    /**
     * @covers \DragonBe\Vies\CheckVatResponse::__construct
     * @covers \DragonBe\Vies\CheckVatResponse::populate
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionIsThrownWhenRequiredParametersAreMissing()
    {
        $vatResponse = new CheckVatResponse([]);
    }

    public function requiredDataProvider()
    {
        return [
            ['DE', '123456749', date('Y-m-dP'), true],
            ['ES', '987654321', date('Y-m-dP'), false],
        ];
    }

    /**
     * @dataProvider requiredDataProvider
     * @covers \DragonBe\Vies\CheckVatResponse::__construct
     * @covers \DragonBe\Vies\CheckVatResponse::populate
     * @covers \DragonBe\Vies\CheckVatResponse::setCountryCode
     * @covers \DragonBe\Vies\CheckVatResponse::getCountryCode
     * @covers \DragonBe\Vies\CheckVatResponse::setVatNumber
     * @covers \DragonBe\Vies\CheckVatResponse::getVatNumber
     * @covers \DragonBe\Vies\CheckVatResponse::setRequestDate
     * @covers \DragonBe\Vies\CheckVatResponse::getRequestDate
     * @covers \DragonBe\Vies\CheckVatResponse::setValid
     * @covers \DragonBe\Vies\CheckVatResponse::isValid
     * @covers \DragonBe\Vies\CheckVatResponse::setName
     * @covers \DragonBe\Vies\CheckVatResponse::getName
     * @covers \DragonBe\Vies\CheckVatResponse::setAddress
     * @covers \DragonBe\Vies\CheckVatResponse::getAddress
     * @covers \DragonBe\Vies\CheckVatResponse::setIdentifier
     * @covers \DragonBe\Vies\CheckVatResponse::getIdentifier
     */
    public function testResponseContainsEmptyValuesWithOnlyRequiredArguments($countryCode, $vatNumber, $requestDate, $valid)
    {
        $expectedResult = [
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
            'requestDate' => substr($requestDate, 0, -6),
            'valid' => $valid,
            'name' => '---',
            'address' => '---',
            'identifier' => '',
        ];
        $vatResponse = new CheckVatResponse([
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
            'requestDate' => new \DateTime($requestDate),
            'valid' => $valid,
        ]);

        $this->assertSame($expectedResult, $vatResponse->toArray());
    }
}
