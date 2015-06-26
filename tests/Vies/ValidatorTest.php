<?php

namespace DragonBe\Test\Vies;

use DragonBe\Vies\Vies;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function vatNumberProvider()
    {
        return array (
            'AT' => array('U10223006', array('U1022300', 'A10223006')),
            'BE' => array('776091951', array('0776091952', '07760919')),
            'BG' => array('101004508', array('10100450')),
            'CY' => array('00532445O', array('0053244511')),
            'CZ' => array('46505334', array('4650533', '123456789A')),
            'DE' => array('111111125', array('111111124', '1234567')),
            'DK' => array('88146328', array('88146327', '1234567')),
            'EE' => array('100207415', array('1002074', 'A12345678')),
            'EL' => array('040127797', array('040127796', '1234567')),
            'ES' => array('A78304516', array('X78204515', 'A78304515', '12345678', 'A783B4515')),
            'FI' => array('09853608', array('09853607', '1234567')),
            'FR' => array('00300076965', array('0030007696A', '1234567890')),
            'HR' => array('38192148118', array('3819214811', '1234567890A')),
            'HU' => array('21376414', array('2137641', '1234567A')),
            'IE' => array('8Z49289F', array('8Z49389F', '1234567')),
            'IT' => array('00000010215', array('00000010214', '1234567890', '00000001234')),
            'LU' => array('10000356', array('10000355', '1234567')),
            'LV' => array('40003009497', array('40003009496', '1234567890', '00212345678')),
            'LT' => array('213179412', array('21317941', '1234567890', '1234567890AB')),
            'MT' => array('15121333', array('15121332', '1234567', '010122222')),
            'NL' => array('010000446B01', array('010000436B01', '12345678901', '123456789A12', '123456789B00')),
            'PL' => array('5260001246', array('12342678090', '1212121212')),
            'PT' => array('502757191', array('502757192', '12345678')),
            'RO' => array('11198699', array('11198698', '1', '12345678902')),
            'SE' => array('556188840401', array('556188840400', '1234567890', '556181140401')),
            'SI' => array('15012557', array('15012556', '12345670', '01234567')),
            'SK' => array('4030000007', array('4030000006', '123456789', '0123456789')),
            'GB' => array('434031494', array('434031493', '12345', 'GD500', 'HA100', '12345678')),
        );
    }

    public function testVatNumberChecksumSuccess()
    {
        $vies = new Vies();

        foreach($this->vatNumberProvider() as $country => $numbers) {
            $result = $vies->validateVatSum($country, $numbers[0]);
            $this->assertTrue($result);
        }
    }

    public function testVatNumberChecksumFailure()
    {
        $vies = new Vies();

        foreach($this->vatNumberProvider() as $country => $numbers) {
            foreach($numbers[1] as $number) {
                $result = $vies->validateVatSum($country, $number);
                $this->assertFalse($result);
            }
        }
    }
}
