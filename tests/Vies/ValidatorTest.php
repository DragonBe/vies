<?php

namespace DragonBe\Test\Vies;

use DragonBe\Vies\Vies;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function vatNumberProvider()
    {
        return array (
            'AT' => array('U10223006', 'U10223005'),
            'BE' => array('0776091951', '0776091952'),
            'BG' => array('101004508', '10100450'),
            'CY' => array('00532445O', '0053244511'),
            'CZ' => array('46505334', '4650533'),
            'DE' => array('111111125', '111111124'),
            'DK' => array('88146328', '88146327'),
            'EE' => array('100207415', '10020744'),
            'EL' => array('040127797', '040127796'),
            'ES' => array('A78304516', 'A78304515'),
            'FI' => array('09853608', '09853607'),
            'FR' => array('00300076965', '0030007696A'),
            'HR' => array('38192148118', '3819214811'),
            'HU' => array('21376414', '2137641'),
            'IE' => array('8Z49289F', '8Z49389F'),
            'IT' => array('00000010215', '00000010214'),
            'LU' => array('10000356', '10000355'),
            'LV' => array('40003009497', '40003009496'),
            'LT' => array('213179412', '21317941'),
            'MT' => array('15121333', '15121332'),
            'NL' => array('010000446B01', '010000436B01'),
            'PL' => array('5260001246', '12345678090'),
            'PT' => array('502757191', '502757192'),
            'RO' => array('11198699', '11198698'),
            'SE' => array('556188840401', '556188840400'),
            'SI' => array('15012557', '15012556'),
            'SK' => array('4030000007', '4030000006'),
            'GB' => array('434031494', '434031493'),
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
            $result = $vies->validateVatSum($country, $numbers[1]);
            $this->assertFalse($result);
        }
    }
}
