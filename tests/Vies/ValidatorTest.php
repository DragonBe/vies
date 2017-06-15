<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use DragonBe\Vies\Vies;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    protected $vies;

    public function setUp()
    {
        $this->vies = new Vies();
    }

    public function tearDown()
    {
        $this->vies = null;
    }

    /**
     * @dataProvider vatNumberValid
     *
     * @param string $country
     * @param string $vatNumber
     */
    public function testValidVatNumber(string $country, string $vatNumber)
    {
        $this->assertTrue($this->vies->validateVatSum($country, $vatNumber));
    }

    public function vatNumberValid()
    {
        return [
            'AT'     => ['country' => 'AT', 'vatNumber' => 'U10223006'],
            'BE'     => ['country' => 'BE', 'vatNumber' => '776091951'],
            'BG'     => ['country' => 'BG', 'vatNumber' => '301004503'],
            'CY'     => ['country' => 'CY', 'vatNumber' => '00532445O'],
            'CZ'     => ['country' => 'CZ', 'vatNumber' => '46505334'],
            'DE'     => ['country' => 'DE', 'vatNumber' => '111111125'],
            'DK'     => ['country' => 'DK', 'vatNumber' => '88146328'],
            'EE'     => ['country' => 'EE', 'vatNumber' => '100207415'],
            'EL'     => ['country' => 'EL', 'vatNumber' => '040127797'],
            'ES (1)' => ['country' => 'ES', 'vatNumber' => 'A0011012B'],
            'ES (2)' => ['country' => 'ES', 'vatNumber' => 'A78304516'],
            'FI'     => ['country' => 'FI', 'vatNumber' => '09853608'],
            'FR (1)' => ['country' => 'FR', 'vatNumber' => '00300076965'],
            'FR (2)' => ['country' => 'FR', 'vatNumber' => 'K7399859412'],
            'FR (3)' => ['country' => 'FR', 'vatNumber' => '4Z123456782'],
            'GB (1)' => ['country' => 'GB', 'vatNumber' => '434031494'],
            'GB (2)' => ['country' => 'GB', 'vatNumber' => 'GD001'],
            'GB (3)' => ['country' => 'GB', 'vatNumber' => 'HA500'],
            'HR'     => ['country' => 'HR', 'vatNumber' => '38192148118'],
            'HU (1)' => ['country' => 'HU', 'vatNumber' => '21376414'],
            'HU (2)' => ['country' => 'HU', 'vatNumber' => '10597190'],
            'IE (1)' => ['country' => 'IE', 'vatNumber' => '8Z49289F'],
            'IE (2)' => ['country' => 'IE', 'vatNumber' => '3628739L'],
            'IE (3)' => ['country' => 'IE', 'vatNumber' => '5343381W'],
            'IE (4)' => ['country' => 'IE', 'vatNumber' => '6433435OA'],
            'IT'     => ['country' => 'IT', 'vatNumber' => '00000010215'],
            'LT (1)' => ['country' => 'LT', 'vatNumber' => '210061371310'],
            'LT (2)' => ['country' => 'LT', 'vatNumber' => '213179412'],
            'LT (3)' => ['country' => 'LT', 'vatNumber' => '290061371314'],
            'LT (4)' => ['country' => 'LT', 'vatNumber' => '208640716'],
            'LU'     => ['country' => 'LU', 'vatNumber' => '10000356'],
            'LV'     => ['country' => 'LV', 'vatNumber' => '40003009497'],
            'MT'     => ['country' => 'MT', 'vatNumber' => '15121333'],
            'NL'     => ['country' => 'NL', 'vatNumber' => '010000446B01'],
            'PL'     => ['country' => 'PL', 'vatNumber' => '5260001246'],
            'PT'     => ['country' => 'PT', 'vatNumber' => '502757191'],
            'RO (1)' => ['country' => 'RO', 'vatNumber' => '11198699'],
            'RO (2)' => ['country' => 'RO', 'vatNumber' => '14186770'],
            'SE'     => ['country' => 'SE', 'vatNumber' => '556188840401'],
            'SI'     => ['country' => 'SI', 'vatNumber' => '15012557'],
            'SK'     => ['country' => 'SK', 'vatNumber' => '4030000007'],
        ];
    }

    /**
     * @dataProvider vatNumberInValid
     *
     * @param string $country
     * @param string $vatNumber
     */
    public function testInValidVatNumber(string $country, string $vatNumber)
    {
        $this->assertFalse($this->vies->validateVatSum($country, $vatNumber));
    }

    public function vatNumberInValid()
    {
        return [
            'AT (1)' => ['country' => 'AT', 'vatNumber' => 'U1022300'],
            'AT (2)' => ['country' => 'AT', 'vatNumber' => 'A10223006'],
            'AT (3)' => ['country' => 'AT', 'vatNumber' => 'U10223005'],
            'BE (1)' => ['country' => 'BE', 'vatNumber' => '0776091952'],
            'BE (2)' => ['country' => 'BE', 'vatNumber' => '07760919'],
            'BG (1)' => ['country' => 'BG', 'vatNumber' => '10100450'],
            'BG (2)' => ['country' => 'BG', 'vatNumber' => '301004502'],
            'CY (1)' => ['country' => 'CY', 'vatNumber' => '005324451'],
            'CY (2)' => ['country' => 'CY', 'vatNumber' => '0053244511'],
            'CY (3)' => ['country' => 'CY', 'vatNumber' => '12000139V'],
            'CY (4)' => ['country' => 'CY', 'vatNumber' => '72000139V'],
            'CZ (1)' => ['country' => 'CZ', 'vatNumber' => '4650533'],
            'CZ (2)' => ['country' => 'CZ', 'vatNumber' => '96505334'],
            'CZ (3)' => ['country' => 'CZ', 'vatNumber' => '46505333'],
            'DE (1)' => ['country' => 'DE', 'vatNumber' => '111111124'],
            'DE (2)' => ['country' => 'DE', 'vatNumber' => '1234567'],
            'DK (1)' => ['country' => 'DK', 'vatNumber' => '88146327'],
            'DK (2)' => ['country' => 'DK', 'vatNumber' => '1234567'],
            'EE (1)' => ['country' => 'EE', 'vatNumber' => '1002074'],
            'EE (2)' => ['country' => 'EE', 'vatNumber' => 'A12345678'],
            'EL (1)' => ['country' => 'EL', 'vatNumber' => '040127796'],
            'EL (2)' => ['country' => 'EL', 'vatNumber' => '1234567'],
            'ES (1)' => ['country' => 'ES', 'vatNumber' => 'K0011012B'],
            'ES (2)' => ['country' => 'ES', 'vatNumber' => '12345678'],
            'ES (3)' => ['country' => 'ES', 'vatNumber' => 'K001A012B'],
            'ES (4)' => ['country' => 'ES', 'vatNumber' => 'A0011012C'],
            'FI (1)' => ['country' => 'FI', 'vatNumber' => '09853607'],
            'FI (2)' => ['country' => 'FI', 'vatNumber' => '1234567'],
            'FR (1)' => ['country' => 'FR', 'vatNumber' => '0030007696A'],
            'FR (2)' => ['country' => 'FR', 'vatNumber' => '1234567890'],
            'FR (3)' => ['country' => 'FR', 'vatNumber' => 'K6399859412'],
            'FR (4)' => ['country' => 'FR', 'vatNumber' => 'KO399859412'],
            'FR (5)' => ['country' => 'FR', 'vatNumber' => 'IO399859412'],
            'GB (1)' => ['country' => 'GB', 'vatNumber' => '434031493'],
            'GB (2)' => ['country' => 'GB', 'vatNumber' => '12345'],
            'GB (3)' => ['country' => 'GB', 'vatNumber' => 'GD500'],
            'GB (4)' => ['country' => 'GB', 'vatNumber' => 'HA100'],
            'GB (5)' => ['country' => 'GB', 'vatNumber' => '12345678'],
            'HR (1)' => ['country' => 'HR', 'vatNumber' => '3819214811'],
            'HR (2)' => ['country' => 'HR', 'vatNumber' => '1234567890A'],
            'HU (1)' => ['country' => 'HU', 'vatNumber' => '2137641'],
            'HU (2)' => ['country' => 'HU', 'vatNumber' => '1234567A'],
            'IE (1)' => ['country' => 'IE', 'vatNumber' => '8Z49389F'],
            'IE (2)' => ['country' => 'IE', 'vatNumber' => '1234567'],
            'IE (3)' => ['country' => 'IE', 'vatNumber' => '6433435OB'],
            'IT (1)' => ['country' => 'IT', 'vatNumber' => '00000010214'],
            'IT (2)' => ['country' => 'IT', 'vatNumber' => '1234567890'],
            'IT (3)' => ['country' => 'IT', 'vatNumber' => '00000001234'],
            'LT (1)' => ['country' => 'LT', 'vatNumber' => '213179422'],
            'LT (2)' => ['country' => 'LT', 'vatNumber' => '21317941'],
            'LT (3)' => ['country' => 'LT', 'vatNumber' => '1234567890'],
            'LT (4)' => ['country' => 'LT', 'vatNumber' => '1234567890AB'],
            'LU (1)' => ['country' => 'LU', 'vatNumber' => '10000355'],
            'LU (2)' => ['country' => 'LU', 'vatNumber' => '1234567'],
            'LV (3)' => ['country' => 'LV', 'vatNumber' => '40013009497'],
            'LV (1)' => ['country' => 'LV', 'vatNumber' => '40003009496'],
            'LV (2)' => ['country' => 'LV', 'vatNumber' => '1234567890'],
            'LV (3)' => ['country' => 'LV', 'vatNumber' => '00212345678'],
            'MT (1)' => ['country' => 'MT', 'vatNumber' => '15121332'],
            'MT (2)' => ['country' => 'MT', 'vatNumber' => '1234567'],
            'MT (3)' => ['country' => 'MT', 'vatNumber' => '05121333'],
            'NL (1)' => ['country' => 'NL', 'vatNumber' => '010000436B01'],
            'NL (2)' => ['country' => 'NL', 'vatNumber' => '12345678901'],
            'NL (3)' => ['country' => 'NL', 'vatNumber' => '123456789A12'],
            'NL (4)' => ['country' => 'NL', 'vatNumber' => '123456789B00'],
            'PL (1)' => ['country' => 'PL', 'vatNumber' => '12342678090'],
            'PL (2)' => ['country' => 'PL', 'vatNumber' => '1212121212'],
            'PT (1)' => ['country' => 'PT', 'vatNumber' => '502757192'],
            'PT (2)' => ['country' => 'PT', 'vatNumber' => '12345678'],
            'RO (1)' => ['country' => 'RO', 'vatNumber' => '11198698'],
            'RO (2)' => ['country' => 'RO', 'vatNumber' => '1'],
            'RO (3)' => ['country' => 'RO', 'vatNumber' => '12345678902'],
            'SE (1)' => ['country' => 'SE', 'vatNumber' => '556188840400'],
            'SE (2)' => ['country' => 'SE', 'vatNumber' => '1234567890'],
            'SE (3)' => ['country' => 'SE', 'vatNumber' => '556181140401'],
            'SI (1)' => ['country' => 'SI', 'vatNumber' => '15012556'],
            'SI (2)' => ['country' => 'SI', 'vatNumber' => '12345670'],
            'SI (3)' => ['country' => 'SI', 'vatNumber' => '01234567'],
            'SI (4)' => ['country' => 'SI', 'vatNumber' => '1234567'],
            'SK (1)' => ['country' => 'SK', 'vatNumber' => '4030000006'],
            'SK (2)' => ['country' => 'SK', 'vatNumber' => '123456789'],
            'SK (3)' => ['country' => 'SK', 'vatNumber' => '0123456789'],
            'SK (4)' => ['country' => 'SK', 'vatNumber' => '4060000007'],
        ];
    }
}
