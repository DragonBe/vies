<?php
namespace DragonBe\Test\Vies;

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
             ->method('checkVat')
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
        $this->assertInstanceOf('\DragonBe\Vies\CheckVatResponse', $response);
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
        $this->assertInstanceOf('\DragonBe\Vies\CheckVatResponse', $response);
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
}