<?php
namespace DragonBe\Test\Vies;

use DragonBe\Vies\Vies;

class ViestTest extends \PHPUnit_Framework_TestCase
{
    public function vatNumberProvider()
    {
        return array (
            array ('0123456789','0123456789'),
            array ('0123 456 789','0123456789'),
            array ('0123.456.789','0123456789'),
            array ('0123-456-789','0123456789'),
        );
    }
    /**
     * @dataProvider vatNumberProvider
     */
    public function testVatNumberFilter($vatNumber, $filteredNumber)
    {
        $this->assertEquals($filteredNumber,
            Vies::filterVat($vatNumber));
    }

    protected function _createdStubbedViesClient($response)
    {
        $stub = $this->getMockFromWsdl(
            dirname(__FILE__) . '/_files/checkVatService.wsdl');
        $stub->expects($this->any())
             ->method('__soapCall')
             ->will($this->returnValue($response));

        $vies = new Vies();
        $vies->setSoapClient($stub);
        return $vies;
    }

    public function testSuccessVatNumberValidation()
    {
        $response = new \StdClass();
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.456.789';
        $response->requestDate = '1983-06-24';
        $response->valid = true;
        $response->name = '';
        $response->address = '';
        
        $vies = $this->_createdStubbedViesClient($response);
        
        $response = $vies->validateVat('BE', '0123.456.789');
        $this->assertInstanceOf('\\DragonBe\\Vies\\CheckVatResponse', $response);
        $this->assertTrue($response->isValid());
        return $response;
    }

    public function testFailureVatNumberValidation()
    {
        $response = new \StdClass();
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.ABC.789';
        $response->requestDate = '1983-06-24';
        $response->valid = false;
        
        $vies = $this->_createdStubbedViesClient($response);

        $response = $vies->validateVat('BE', '0123.ABC.789');
        $this->assertInstanceOf('\\DragonBe\\Vies\\CheckVatResponse', $response);
        $this->assertFalse($response->isValid());
    }

    /**
     * @depends testSuccessVatNumberValidation
     */
    public function testConvertObjectIntoArray($response)
    {
        $array = $response->toArray();
        $this->assertSame('BE', $array['countryCode']);
        $this->assertSame('0123.456.789', $array['vatNumber']);
        $this->assertSame('1983-06-24', $array['requestDate']);
        $this->assertTrue($array['valid']);
        $this->assertEmpty($array['name']);
        $this->assertEmpty($array['address']);
    }

    private function createHeartBeatMock($bool)
    {
        $heartBeatMock = $this->getMock(
            '\\DragonBe\\Vies\\HeartBeat',
            array ('isAlive')
        );
        $heartBeatMock->expects($this->once())
            ->method('isAlive')
            ->will($this->returnValue($bool));
        return $heartBeatMock;
    }

    public function testServiceIsAlive()
    {
        $vies = new Vies();
        $hb = $this->createHeartBeatMock(true);
        $vies->setHeartBeat($hb);
        $this->assertTrue(
            $vies->getHeartBeat()->isAlive()
        );
    }

    public function testServiceIsDown()
    {
        $vies = new Vies();
        $hb = $this->createHeartBeatMock(false);
        $vies->setHeartBeat($hb);
        $this->assertFalse(
            $vies->getHeartBeat()->isAlive()
        );
    }
}
