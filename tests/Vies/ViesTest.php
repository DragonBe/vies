<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use DragonBe\Vies\CheckVatResponse;
use DragonBe\Vies\HeartBeat;
use DragonBe\Vies\Request;
use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;
use PHPUnit\Framework\TestCase;
use SoapClient;
use SoapFault;

/**
 * @coversDefaultClass \DragonBe\Vies\Vies
 */
class ViestTest extends TestCase
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

    public function testExtendedRequestParams()
    {
        $request = new Request();
        $request->setRequesterVatNumber('012-34-56748');
        $request->setRequesterCountryCode('DE');
        $request->setVatNumber('0123.456.749');
        $request->setCountryCode('BE');
        $request->setTraderName('MegaCorp');
        $request->setTraderCompanyType('el-23');
        $request->setTraderStreet('Galaxy Road');
        $request->setTraderPostcode('1337PI');
        $request->setTraderCity('Gotham');

        $response = (object) [];
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.ABC.749';
        $response->requestDate = '1983-06-24+23:59';
        $response->valid = false;

        $stub = $this->getMockFromWsdl(dirname(__FILE__) . '/_files/checkVatService.wsdl');
        $stub->expects($this->exactly(1))
            ->method('__soapCall')
            ->with('checkVatApprox', [[
                'requesterCountryCode' => 'DE',
                'requesterVatNumber' => '0123456748',
                'countryCode' => 'BE',
                'vatNumber' => '0123456749',
                'traderName' => 'MegaCorp',
                'traderCompanyType' => 'el-23',
                'traderStreet' => 'Galaxy Road',
                'traderPostcode' => '1337PI',
                'traderCity' => 'Gotham',
            ]])
            ->willReturn($response);

        $ret = (new Vies())
            ->setSoapClient($stub)
            ->validateVatRequest($request)
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
        $this->assertSame('tcp://' . Vies::VIES_DOMAIN, $hb->getHost());
        $this->assertSame(80, $hb->getPort());
    }

    /**
     * @covers ::listEuropeanCountries
     */
    public function testRetrievingListOfEuropeanCountriesStatically()
    {
        $this->assertCount(Vies::VIES_EU_COUNTRY_TOTAL, Vies::listEuropeanCountries());
    }
}
