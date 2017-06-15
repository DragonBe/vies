<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use DateTime;
use DragonBe\Vies\CheckVatResponse;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class CheckVatResponseTest
 *
 * @package DragonBe\Test\Vies
 * @coversDefaultClass \DragonBe\Vies\CheckVatResponse
 */
class CheckVatResponseTest extends TestCase
{
    /**
     * @param bool $isValid
     * @return array
     */
    protected function createViesResponseArray($isValid = true)
    {
        return [
            'countryCode' => 'BE',
            'vatNumber'   => '123456749',
            'requestDate' => date_create(date('Y-m-dP')),
            'valid'       => $isValid,
            'traderName'        => 'Testing Corp N.V.',
            'traderAddress'     => 'MARKT 1' . PHP_EOL . '1000  BRUSSEL',
            'requestIdentifier' => 'XYZ1234567890'
        ];
    }

    /**
     * @param bool $isValid
     * @return stdClass
     */
    protected function createViesResponse($isValid = true)
    {
        return (object) $this->createViesResponseArray($isValid);
    }

    public function validationProvider()
    {
        return  [
             [true],
             [false]
        ];
    }

    /**
     * Test that a VAT response can be created
     *
     * @dataProvider validationProvider
     * @covers ::__construct
     * @covers ::populate
     * @covers ::setCountryCode
     * @covers ::getCountryCode
     * @covers ::setVatNumber
     * @covers ::getVatNumber
     * @covers ::setRequestDate
     * @covers ::getRequestDate
     * @covers ::setValid
     * @covers ::isValid
     * @covers ::setName
     * @covers ::getName
     * @covers ::setAddress
     * @covers ::getAddress
     * @covers ::setIdentifier
     * @covers ::getIdentifier
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
     * @covers ::__construct
     * @covers ::populate
     * @covers ::setCountryCode
     * @covers ::getCountryCode
     * @covers ::setVatNumber
     * @covers ::getVatNumber
     * @covers ::setRequestDate
     * @covers ::getRequestDate
     * @covers ::setValid
     * @covers ::isValid
     * @covers ::setName
     * @covers ::getName
     * @covers ::setAddress
     * @covers ::getAddress
     * @covers ::setIdentifier
     * @covers ::getIdentifier
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
     * @covers ::__construct
     * @covers ::populate
     * @covers ::setCountryCode
     * @covers ::getCountryCode
     * @covers ::setVatNumber
     * @covers ::getVatNumber
     * @covers ::setRequestDate
     * @covers ::getRequestDate
     * @covers ::setValid
     * @covers ::isValid
     * @covers ::setName
     * @covers ::getName
     * @covers ::setAddress
     * @covers ::getAddress
     * @covers ::setIdentifier
     * @covers ::getIdentifier
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
     * @covers ::__construct
     * @covers ::getRequestDate
     */
    public function testDefaultDateIsNow()
    {
        $vatResponse = new CheckVatResponse();
        $this->assertInstanceOf(DateTime::class, $vatResponse->getRequestDate());
        $this->assertSame(date('Y-m-dP'), $vatResponse->getRequestDate()->format('Y-m-dP'));
    }

    /**
     * @covers ::__construct
     * @covers ::populate
     */
    public function testExceptionIsThrownWhenRequiredParametersAreMissing()
    {
        $this->expectException(InvalidArgumentException::class);
        new CheckVatResponse([]);
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
     * @covers ::__construct
     * @covers ::populate
     * @covers ::setCountryCode
     * @covers ::getCountryCode
     * @covers ::setVatNumber
     * @covers ::getVatNumber
     * @covers ::setRequestDate
     * @covers ::getRequestDate
     * @covers ::setValid
     * @covers ::isValid
     * @covers ::setName
     * @covers ::getName
     * @covers ::setAddress
     * @covers ::getAddress
     * @covers ::setIdentifier
     * @covers ::getIdentifier
     */
    public function testResponseContainsEmptyValuesWithOnlyRequiredArguments(
        $countryCode,
        $vatNumber,
        $requestDate,
        $valid
    ) {

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
            'requestDate' => date_create($requestDate),
            'valid' => $valid,
        ]);

        $this->assertSame($expectedResult, $vatResponse->toArray());
    }
}
