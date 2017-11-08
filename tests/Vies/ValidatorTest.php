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
     * @covers \DragonBe\Vies\Validator\ValidatorAbstract<extended>
     * @covers \DragonBe\Vies\Vies::validateVatSum
     *
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
            'AT'      => ['country' => 'AT', 'vatNumber' => 'U10223006'],
            'BE'      => ['country' => 'BE', 'vatNumber' => '776091951'],
            'BG'      => ['country' => 'BG', 'vatNumber' => '301004503'],
            'CY'      => ['country' => 'CY', 'vatNumber' => '00532445O'],
            'CZ (01)' => ['country' => 'CZ', 'vatNumber' => '46505334'],
            'CZ (02)' => ['country' => 'CZ', 'vatNumber' => '7103192745'],
            'CZ (03)' => ['country' => 'CZ', 'vatNumber' => '640903926'],
            'CZ (04)' => ['country' => 'CZ', 'vatNumber' => '395601439'],
            'CZ (05)' => ['country' => 'CZ', 'vatNumber' => '630903928'],
            'CZ (06)' => ['country' => 'CZ', 'vatNumber' => '27082440'],
            'DE'      => ['country' => 'DE', 'vatNumber' => '111111125'],
            'DK'      => ['country' => 'DK', 'vatNumber' => '88146328'],
            'EE'      => ['country' => 'EE', 'vatNumber' => '100207415'],
            'EL'      => ['country' => 'EL', 'vatNumber' => '040127797'],
            'ES (01)' => ['country' => 'ES', 'vatNumber' => 'A0011012B'],
            'ES (02)' => ['country' => 'ES', 'vatNumber' => 'A78304516'],
            'FI'      => ['country' => 'FI', 'vatNumber' => '09853608'],
            'FR (01)' => ['country' => 'FR', 'vatNumber' => '00300076965'],
            'FR (02)' => ['country' => 'FR', 'vatNumber' => 'K7399859412'],
            'FR (03)' => ['country' => 'FR', 'vatNumber' => '4Z123456782'],
            'GB (01)' => ['country' => 'GB', 'vatNumber' => '434031494'],
            'GB (02)' => ['country' => 'GB', 'vatNumber' => 'GD001'],
            'GB (03)' => ['country' => 'GB', 'vatNumber' => 'HA500'],
            'HR'      => ['country' => 'HR', 'vatNumber' => '38192148118'],
            'HU (01)' => ['country' => 'HU', 'vatNumber' => '21376414'],
            'HU (02)' => ['country' => 'HU', 'vatNumber' => '10597190'],
            'IE (01)' => ['country' => 'IE', 'vatNumber' => '8Z49289F'],
            'IE (02)' => ['country' => 'IE', 'vatNumber' => '3628739L'],
            'IE (03)' => ['country' => 'IE', 'vatNumber' => '5343381W'],
            'IE (04)' => ['country' => 'IE', 'vatNumber' => '6433435OA'],
            'IT'      => ['country' => 'IT', 'vatNumber' => '00000010215'],
            'LT (01)' => ['country' => 'LT', 'vatNumber' => '210061371310'],
            'LT (02)' => ['country' => 'LT', 'vatNumber' => '213179412'],
            'LT (03)' => ['country' => 'LT', 'vatNumber' => '290061371314'],
            'LT (04)' => ['country' => 'LT', 'vatNumber' => '208640716'],
            'LU'      => ['country' => 'LU', 'vatNumber' => '10000356'],
            'LV'      => ['country' => 'LV', 'vatNumber' => '40003009497'],
            'MT'      => ['country' => 'MT', 'vatNumber' => '15121333'],
            'NL'      => ['country' => 'NL', 'vatNumber' => '010000446B01'],
            'PL'      => ['country' => 'PL', 'vatNumber' => '5260001246'],
            'PT'      => ['country' => 'PT', 'vatNumber' => '502757191'],
            'RO (01)' => ['country' => 'RO', 'vatNumber' => '11198699'],
            'RO (02)' => ['country' => 'RO', 'vatNumber' => '14186770'],
            'SE'      => ['country' => 'SE', 'vatNumber' => '556188840401'],
            'SI'      => ['country' => 'SI', 'vatNumber' => '15012557'],
            'SK'      => ['country' => 'SK', 'vatNumber' => '4030000007'],
        ];
    }

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorAbstract<extended>
     * @covers \DragonBe\Vies\Vies::validateVatSum
     *
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
            'AT (01)' => ['country' => 'AT', 'vatNumber' => 'U1022300'],
            'AT (02)' => ['country' => 'AT', 'vatNumber' => 'A10223006'],
            'AT (03)' => ['country' => 'AT', 'vatNumber' => 'U10223005'],
            'BE (01)' => ['country' => 'BE', 'vatNumber' => '0776091952'],
            'BE (02)' => ['country' => 'BE', 'vatNumber' => '07760919'],
            'BG (01)' => ['country' => 'BG', 'vatNumber' => '10100450'],
            'BG (02)' => ['country' => 'BG', 'vatNumber' => '301004502'],
            'CY (01)' => ['country' => 'CY', 'vatNumber' => '005324451'],
            'CY (02)' => ['country' => 'CY', 'vatNumber' => '0053244511'],
            'CY (03)' => ['country' => 'CY', 'vatNumber' => '12000139V'],
            'CY (04)' => ['country' => 'CY', 'vatNumber' => '72000139V'],
            'CZ (01)' => ['country' => 'CZ', 'vatNumber' => '4650533'],
            'CZ (02)' => ['country' => 'CZ', 'vatNumber' => '96505334'],
            'CZ (03)' => ['country' => 'CZ', 'vatNumber' => '46505333'],
            'CZ (04)' => ['country' => 'CZ', 'vatNumber' => '7103192743'],
            'CZ (05)' => ['country' => 'CZ', 'vatNumber' => '1903192745'],
            'CZ (06)' => ['country' => 'CZ', 'vatNumber' => '7133192745'],
            'CZ (07)' => ['country' => 'CZ', 'vatNumber' => '395632439'],
            'CZ (08)' => ['country' => 'CZ', 'vatNumber' => '396301439'],
            'CZ (09)' => ['country' => 'CZ', 'vatNumber' => '545601439'],
            'CZ (10)' => ['country' => 'CZ', 'vatNumber' => '640903927'],
            'CZ (11)' => ['country' => 'CZ', 'vatNumber' => '7103322745'],
            'DE (01)' => ['country' => 'DE', 'vatNumber' => '111111124'],
            'DE (02)' => ['country' => 'DE', 'vatNumber' => '1234567'],
            'DK (01)' => ['country' => 'DK', 'vatNumber' => '88146327'],
            'DK (02)' => ['country' => 'DK', 'vatNumber' => '1234567'],
            'EE (01)' => ['country' => 'EE', 'vatNumber' => '1002074'],
            'EE (02)' => ['country' => 'EE', 'vatNumber' => 'A12345678'],
            'EL (01)' => ['country' => 'EL', 'vatNumber' => '040127796'],
            'EL (02)' => ['country' => 'EL', 'vatNumber' => '1234567'],
            'ES (01)' => ['country' => 'ES', 'vatNumber' => 'K0011012B'],
            'ES (02)' => ['country' => 'ES', 'vatNumber' => '12345678'],
            'ES (03)' => ['country' => 'ES', 'vatNumber' => 'K001A012B'],
            'ES (04)' => ['country' => 'ES', 'vatNumber' => 'A0011012C'],
            'FI (01)' => ['country' => 'FI', 'vatNumber' => '09853607'],
            'FI (02)' => ['country' => 'FI', 'vatNumber' => '1234567'],
            'FR (01)' => ['country' => 'FR', 'vatNumber' => '0030007696A'],
            'FR (02)' => ['country' => 'FR', 'vatNumber' => '1234567890'],
            'FR (03)' => ['country' => 'FR', 'vatNumber' => 'K6399859412'],
            'FR (04)' => ['country' => 'FR', 'vatNumber' => 'KO399859412'],
            'FR (05)' => ['country' => 'FR', 'vatNumber' => 'IO399859412'],
            'GB (01)' => ['country' => 'GB', 'vatNumber' => '434031493'],
            'GB (02)' => ['country' => 'GB', 'vatNumber' => '12345'],
            'GB (03)' => ['country' => 'GB', 'vatNumber' => 'GD500'],
            'GB (04)' => ['country' => 'GB', 'vatNumber' => 'HA100'],
            'GB (05)' => ['country' => 'GB', 'vatNumber' => '12345678'],
            'HR (01)' => ['country' => 'HR', 'vatNumber' => '3819214811'],
            'HR (02)' => ['country' => 'HR', 'vatNumber' => '1234567890A'],
            'HU (01)' => ['country' => 'HU', 'vatNumber' => '2137641'],
            'HU (02)' => ['country' => 'HU', 'vatNumber' => '1234567A'],
            'IE (01)' => ['country' => 'IE', 'vatNumber' => '8Z49389F'],
            'IE (02)' => ['country' => 'IE', 'vatNumber' => '1234567'],
            'IE (03)' => ['country' => 'IE', 'vatNumber' => '6433435OB'],
            'IT (01)' => ['country' => 'IT', 'vatNumber' => '00000010214'],
            'IT (02)' => ['country' => 'IT', 'vatNumber' => '1234567890'],
            'IT (03)' => ['country' => 'IT', 'vatNumber' => '00000001234'],
            'LT (01)' => ['country' => 'LT', 'vatNumber' => '213179422'],
            'LT (02)' => ['country' => 'LT', 'vatNumber' => '21317941'],
            'LT (03)' => ['country' => 'LT', 'vatNumber' => '1234567890'],
            'LT (04)' => ['country' => 'LT', 'vatNumber' => '1234567890AB'],
            'LU (01)' => ['country' => 'LU', 'vatNumber' => '10000355'],
            'LU (02)' => ['country' => 'LU', 'vatNumber' => '1234567'],
            'LV (03)' => ['country' => 'LV', 'vatNumber' => '40013009497'],
            'LV (01)' => ['country' => 'LV', 'vatNumber' => '40003009496'],
            'LV (02)' => ['country' => 'LV', 'vatNumber' => '1234567890'],
            'LV (03)' => ['country' => 'LV', 'vatNumber' => '00212345678'],
            'MT (01)' => ['country' => 'MT', 'vatNumber' => '15121332'],
            'MT (02)' => ['country' => 'MT', 'vatNumber' => '1234567'],
            'MT (03)' => ['country' => 'MT', 'vatNumber' => '05121333'],
            'NL (01)' => ['country' => 'NL', 'vatNumber' => '010000436B01'],
            'NL (02)' => ['country' => 'NL', 'vatNumber' => '12345678901'],
            'NL (03)' => ['country' => 'NL', 'vatNumber' => '123456789A12'],
            'NL (04)' => ['country' => 'NL', 'vatNumber' => '123456789B00'],
            'PL (01)' => ['country' => 'PL', 'vatNumber' => '12342678090'],
            'PL (02)' => ['country' => 'PL', 'vatNumber' => '1212121212'],
            'PT (01)' => ['country' => 'PT', 'vatNumber' => '502757192'],
            'PT (02)' => ['country' => 'PT', 'vatNumber' => '12345678'],
            'RO (01)' => ['country' => 'RO', 'vatNumber' => '11198698'],
            'RO (02)' => ['country' => 'RO', 'vatNumber' => '1'],
            'RO (03)' => ['country' => 'RO', 'vatNumber' => '12345678902'],
            'SE (01)' => ['country' => 'SE', 'vatNumber' => '556188840400'],
            'SE (02)' => ['country' => 'SE', 'vatNumber' => '1234567890'],
            'SE (03)' => ['country' => 'SE', 'vatNumber' => '556181140401'],
            'SI (01)' => ['country' => 'SI', 'vatNumber' => '15012556'],
            'SI (02)' => ['country' => 'SI', 'vatNumber' => '12345670'],
            'SI (03)' => ['country' => 'SI', 'vatNumber' => '01234567'],
            'SI (04)' => ['country' => 'SI', 'vatNumber' => '1234567'],
            'SK (01)' => ['country' => 'SK', 'vatNumber' => '4030000006'],
            'SK (02)' => ['country' => 'SK', 'vatNumber' => '123456789'],
            'SK (03)' => ['country' => 'SK', 'vatNumber' => '0123456789'],
            'SK (04)' => ['country' => 'SK', 'vatNumber' => '4060000007'],
        ];
    }
}
