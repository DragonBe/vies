<?php
class My_Service_ViestTest extends PHPUnit_Framework_TestCase
{
    protected function _createMockResponse($response)
    {
        $stub = $this->getMock('My_Service_Vies');
        $stub->expects($this->any())
             ->method('validateVat')
             ->will($this->returnValue($response));
        return $stub;
    }
    public function testGoodValidationOfVatNumber()
    {
        $response = new StdClass();
        $response->countryCode = 'NL';
        $response->vatNumber = '0123-456-789';
        $response->requestDate = '1983-06-24';
        $response->valid = true;
        $response->name = '';
        $response->address = '';
        
//        $vies = $this->_createMockResponse($response);
//        
//        $actual = $vies->validateVat('NL', '0123-456-789');
//        $this->assertEquals($response, $actual);
//        $this->assertTrue($actual->valid);
    }
    public function testBadValidationOfVatNumber()
    {
        $response = new StdClass();
        $response->countryCode = 'BE';
        $response->vatNumber = '9876-ABC-321';
        $response->requestDate = '1983-06-24';
        $response->valid = false;
        
//        $vies = $this->_createMockResponse($response);
//        
//        $actual = $vies->validateVat('BE', '9876-ABC-321');
//        $this->assertEquals($response, $actual);
//        $this->assertFalse($actual->valid);
    }
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
            My_Service_Vies::filterVat($vatNumber));
    }
    protected function _createdStubbedViesClient($response)
    {
        $stub = $this->getMockFromWsdl(
            My_Service_Vies::VIES_WSDL);
        $stub->expects($this->any())
             ->method('checkVat')
             ->will($this->returnValue($response));
        $vies = new My_Service_Vies();
        $vies->setSoapClient($stub);
        return $vies;
    }
    public function testSuccessVatNumberValidation()
    {
        $response = new StdClass();
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.456.789';
        $response->requestDate = '1983-06-24';
        $response->valid = true;
        $response->name = '';
        $response->address = '';
        
        $vies = $this->_createdStubbedViesClient($response);
        
        $response = $vies->validateVat('BE', '0123.456.789');
        $this->assertInstanceOf('My_Service_Vies_CheckVatResponse', $response);
        $this->assertTrue($response->isValid());
    }
    public function testFailureVatNumberValidation()
    {
        $response = new StdClass();
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.ABC.789';
        $response->requestDate = '1983-06-24';
        $response->valid = false;
        
        $vies = $this->_createdStubbedViesClient($response);

        $response = $vies->validateVat('BE', '0123.ABC.789');
        $this->assertInstanceOf('My_Service_Vies_CheckVatResponse', $response);
        $this->assertFalse($response->isValid());
    }
}