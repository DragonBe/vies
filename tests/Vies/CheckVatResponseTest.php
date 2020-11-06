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
     *
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
     *
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
     * @covers ::setNameMatch
     * @covers ::getNameMatch
     * @covers ::setCompanyTypeMatch
     * @covers ::getCompanyTypeMatch
     * @covers ::setStreetMatch
     * @covers ::getStreetMatch
     * @covers ::setPostcodeMatch
     * @covers ::getPostcodeMatch
     * @covers ::setCityMatch
     * @covers ::getCityMatch
     *
     * @dataProvider validationProvider
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
     * @covers ::setNameMatch
     * @covers ::getNameMatch
     * @covers ::setCompanyTypeMatch
     * @covers ::getCompanyTypeMatch
     * @covers ::setStreetMatch
     * @covers ::getStreetMatch
     * @covers ::setPostcodeMatch
     * @covers ::getPostcodeMatch
     * @covers ::setCityMatch
     * @covers ::getCityMatch
     *
     * @dataProvider validationProvider
     */
    public function testCanCreateResponseWithoutNameAndAddressAtConstruct($validCheck)
    {
        $response = $this->createViesResponse($validCheck);
        unset($response->traderName, $response->traderAddress);
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
     * @covers ::setNameMatch
     * @covers ::getNameMatch
     * @covers ::setCompanyTypeMatch
     * @covers ::getCompanyTypeMatch
     * @covers ::setStreetMatch
     * @covers ::getStreetMatch
     * @covers ::setPostcodeMatch
     * @covers ::getPostcodeMatch
     * @covers ::setCityMatch
     * @covers ::getCityMatch
     *
     * @dataProvider validationProvider
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
     * @covers ::setNameMatch
     * @covers ::getNameMatch
     * @covers ::setCompanyTypeMatch
     * @covers ::getCompanyTypeMatch
     * @covers ::setStreetMatch
     * @covers ::getStreetMatch
     * @covers ::setPostcodeMatch
     * @covers ::getPostcodeMatch
     * @covers ::setCityMatch
     * @covers ::getCityMatch
     *
     * @dataProvider requiredDataProvider
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
            'nameMatch' => '',
            'companyTypeMatch' => '',
            'streetMatch' => '',
            'postcodeMatch' => '',
            'cityMatch' => '',
        ];

        $vatResponse = new CheckVatResponse([
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
            'requestDate' => date_create($requestDate),
            'valid' => $valid,
        ]);

        $this->assertSame($expectedResult, $vatResponse->toArray());
    }

    /**
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
     * @covers ::setNameMatch
     * @covers ::getNameMatch
     * @covers ::setCompanyTypeMatch
     * @covers ::getCompanyTypeMatch
     * @covers ::setStreetMatch
     * @covers ::getStreetMatch
     * @covers ::setPostcodeMatch
     * @covers ::getPostcodeMatch
     * @covers ::setCityMatch
     * @covers ::getCityMatch
     *
     * @dataProvider requiredDataProvider
     */
    public function testResponseAcceptsStringDates(
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
            'nameMatch' => '',
            'companyTypeMatch' => '',
            'streetMatch' => '',
            'postcodeMatch' => '',
            'cityMatch' => '',
        ];

        $vatResponse = new CheckVatResponse([
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
            'requestDate' => $requestDate,
            'valid' => $valid,
        ]);

        $this->assertSame($expectedResult, $vatResponse->toArray());
    }

    /**
     * Generates trader details that can be submitted to VIES
     * as an additional check
     *
     * @return array
     */
    public function traderDetailsProvider(): array
    {
        return [
            [
                'BE',
                '0123456789',
                'FooBar',
                'bvba',
                'Kerkstraat 1234',
                '2000',
                'Antwerpen',
            ],
            [
                'NL',
                '0123456789',
                'De vrolijke testers',
                'BV',
                'Kerkstraat 12',
                '1017 GC',
                'Amsterdam',
            ],
            [
                'DE',
                '0123456789',
                'Die fröhlichen Tester',
                'GmbH',
                'Kaiserstraße 14',
                '53113',
                'Bonn',
            ],

        ];
    }

    /**
     * @param string $countryCode
     * @param string $vatNumber
     * @param string $companyName
     * @param string $companyType
     * @param string $companyStreet
     * @param string $companyPostcode
     * @param string $companyCity
     *
     * @dataProvider traderDetailsProvider
     * @covers ::toArray
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
     * @covers ::setNameMatch
     * @covers ::getNameMatch
     * @covers ::setCompanyTypeMatch
     * @covers ::getCompanyTypeMatch
     * @covers ::setStreetMatch
     * @covers ::getStreetMatch
     * @covers ::setPostcodeMatch
     * @covers ::getPostcodeMatch
     * @covers ::setCityMatch
     * @covers ::getCityMatch
     */
    public function testValidatingTraderDetails(
        string $countryCode,
        string $vatNumber,
        string $companyName,
        string $companyType,
        string $companyStreet,
        string $companyPostcode,
        string $companyCity
    ) {
        $requestDate = date('Y-m-dP');
        $valid = true;
        $identifier = substr(md5('The world is not enough...'), 0, 16);

        $expectedResult = [
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
            'requestDate' => substr($requestDate, 0, -6),
            'valid' => $valid,
            'name' => strtoupper($companyType . ' ' . $companyName),
            'address' => strtoupper($companyStreet . ' ' . $companyPostcode . ' ' . $companyCity),
            'identifier' => $identifier,
            'nameMatch' => '',
            'companyTypeMatch' => '',
            'streetMatch' => '',
            'postcodeMatch' => '',
            'cityMatch' => '',
        ];

        $vatResponse = new CheckVatResponse([
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
            'requestDate' => date_create($requestDate),
            'valid' => $valid,
            'traderName' => strtoupper($companyType . ' ' . $companyName),
            'traderAddress' => strtoupper($companyStreet . ' ' . $companyPostcode . ' ' . $companyCity),
            'requestIdentifier' => $identifier,
        ]);

        $this->assertSame($expectedResult, $vatResponse->toArray());
    }
}
