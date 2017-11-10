<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use DragonBe\Vies\Vies;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    protected function validateVatNumber(string $country, string $vatNumber, bool $state)
    {
        $this->assertSame($state, (new Vies())->validateVatSum($country, $vatNumber));
    }

    //ValidatorAT

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorAT
     * @dataProvider vatNumberProviderForAT
     */
    public function testValidatorAT(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('AT', $vatNumber, $state);
    }

    public function vatNumberProviderForAT()
    {
        return [
            ['U10223006', true],
            ['U1022300', false],
            ['A10223006', false],
            ['U10223005', false],
        ];
    }

    //ValidatorBG

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorBG
     * @dataProvider vatNumberProviderForBG
     */
    public function testValidatorBG(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('BG', $vatNumber, $state);
    }

    public function vatNumberProviderForBG()
    {
        return [
            ['301004503', true],
            ['10100450', false],
            ['301004502', false],
        ];
    }

    //ValidatorBE

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorBE
     * @dataProvider vatNumberProviderForBE
     */
    public function testValidatorBE(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('BE', $vatNumber, $state);
    }

    public function vatNumberProviderForBE()
    {
        return [
            ['776091951', true],
            ['0776091952', false],
            ['07760919', false],
        ];
    }

    //ValidatorCY

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorCY
     * @dataProvider vatNumberProviderForCY
     */
    public function testValidatorCY(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('CY', $vatNumber, $state);
    }

    public function vatNumberProviderForCY()
    {
        return [
            ['00532445O', true],
            ['005324451', false],
            ['0053244511', false],
            ['12000139V', false],
            ['72000139V', false],
        ];
    }

    //ValidatorCZ

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorCZ
     * @dataProvider vatNumberProviderForCZ
     */
    public function testValidatorCZ(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('CZ', $vatNumber, $state);
    }

    public function vatNumberProviderForCZ()
    {
        return [
            ['46505334', true],
            ['7103192745', true],
            ['640903926', true],
            ['395601439', true],
            ['630903928', true],
            ['27082440', true],
            ['4650533', false],
            ['96505334', false],
            ['46505333', false],
            ['7103192743', false],
            ['1903192745', false],
            ['7133192745', false],
            ['395632439', false],
            ['396301439', false],
            ['545601439', false],
            ['640903927', false],
            ['7103322745', false],
        ];
    }

    //ValidatorDE

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorDE
     * @dataProvider vatNumberProviderForDE
     */
    public function testValidatorDE(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('DE', $vatNumber, $state);
    }

    public function vatNumberProviderForDE()
    {
        return [
            ['111111125', true],
            ['111111124', false],
            ['1234567', false],
        ];
    }

    //ValidatorDK

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorDK
     * @dataProvider vatNumberProviderForDK
     * @param string $vatNumber
     */
    public function testValidatorDK(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('DK', $vatNumber, $state);
    }

    public function vatNumberProviderForDK()
    {
        return [
            ['88146328', true],
            ['88146327', false],
            ['1234567', false],
        ];
    }

    //ValidatorEE

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorEE
     * @dataProvider vatNumberProviderForEE
     * @param string $vatNumber
     */
    public function testValidatorEE(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('EE', $vatNumber, $state);
    }

    public function vatNumberProviderForEE()
    {
        return [
            ['100207415', true],
            ['1002074', false],
            ['A12345678', false],
        ];
    }

    //ValidatorEL

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorEL
     * @dataProvider vatNumberProviderForEL
     * @param string $vatNumber
     */
    public function testValidatorEL(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('EL', $vatNumber, $state);
    }

    public function vatNumberProviderForEL()
    {
        return [
            ['040127797', true],
            ['040127796', false],
            ['1234567', false],
        ];
    }

    //ValidatorES

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorES
     * @dataProvider vatNumberProviderForES
     * @param string $vatNumber
     */
    public function testValidatorES(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('ES', $vatNumber, $state);
    }

    public function vatNumberProviderForES()
    {
        return [
            ['A0011012B', true],
            ['A78304516', true],
            ['K0011012B', false],
            ['12345678', false],
            ['K001A012B', false],
            ['A0011012C', false],
        ];
    }

    //ValidatorFI

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorFI
     * @dataProvider vatNumberProviderForFI
     * @param string $vatNumber
     */
    public function testValidatorFI(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('FI', $vatNumber, $state);
    }

    public function vatNumberProviderForFI()
    {
        return [
            ['09853608', true],
            ['09853607', false],
            ['1234567', false],
        ];
    }

    //ValidatorFR

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorFR
     * @dataProvider vatNumberProviderForFR
     * @param string $vatNumber
     */
    public function testValidatorFR(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('FR', $vatNumber, $state);
    }

    public function vatNumberProviderForFR()
    {
        return [
            ['00300076965', true],
            ['K7399859412', true],
            ['4Z123456782', true],
            ['0030007696A', false],
            ['1234567890', false],
            ['K6399859412', false],
            ['KO399859412', false],
            ['IO399859412', false],
        ];
    }

    //ValidatorGB

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorGB
     * @dataProvider vatNumberProviderForGB
     * @param string $vatNumber
     */
    public function testValidatorGB(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('GB', $vatNumber, $state);
    }

    public function vatNumberProviderForGB()
    {
        return [
            ['434031494', true],
            ['GD001', true],
            ['HA500', true],
            ['434031493', false],
            ['12345', false],
            ['GD500', false],
            ['HA100', false],
            ['12345678', false],
        ];
    }

    //ValidatorHR

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorHR
     * @dataProvider vatNumberProviderForHR
     * @param string $vatNumber
     */
    public function testValidatorHR(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('HR', $vatNumber, $state);
    }

    public function vatNumberProviderForHR()
    {
        return [
            ['38192148118', true],
            ['3819214811', false],
            ['1234567890A', false],
        ];
    }

    //ValidatorHU

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorHU
     * @dataProvider vatNumberProviderForHU
     * @param string $vatNumber
     */
    public function testValidatorHU(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('HU', $vatNumber, $state);
    }

    public function vatNumberProviderForHU()
    {
        return [
            ['21376414', true],
            ['10597190', true],
            ['2137641', false],
            ['1234567A', false],
        ];
    }

    //ValidatorIE

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorIE
     * @dataProvider vatNumberProviderForIE
     * @param string $vatNumber
     */
    public function testValidatorIE(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('IE', $vatNumber, $state);
    }

    public function vatNumberProviderForIE()
    {
        return [
            ['8Z49289F', true],
            ['3628739L', true],
            ['5343381W', true],
            ['6433435OA', true],
            ['8Z49389F', false],
            ['1234567', false],
            ['6433435OB', false],
        ];
    }

    //ValidatorIT

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorIT
     * @dataProvider vatNumberProviderForIT
     * @param string $vatNumber
     */
    public function testValidatorIT(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('IT', $vatNumber, $state);
    }

    public function vatNumberProviderForIT()
    {
        return [
            ['00000010215', true],
            ['00000010214', false],
            ['1234567890', false],
            ['00000001234', false],
        ];
    }

    //ValidatorLT

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorLT
     * @dataProvider vatNumberProviderForLT
     * @param string $vatNumber
     */
    public function testValidatorLT(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('LT', $vatNumber, $state);
    }

    public function vatNumberProviderForLT()
    {
        return [
            ['210061371310', true],
            ['213179412', true],
            ['290061371314', true],
            ['208640716', true],
            ['213179422', false],
            ['21317941', false],
            ['1234567890', false],
            ['1234567890AB', false],
        ];
    }

    //ValidatorLU

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorLU
     * @dataProvider vatNumberProviderForLU
     * @param string $vatNumber
     */
    public function testValidatorLU(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('LU', $vatNumber, $state);
    }

    public function vatNumberProviderForLU()
    {
        return [
            ['10000356', true],
            ['10000355', false],
            ['1234567', false],
        ];
    }

    //ValidatorLV

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorLV
     * @dataProvider vatNumberProviderForLV
     * @param string $vatNumber
     */
    public function testValidatorLV(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('LV', $vatNumber, $state);
    }

    public function vatNumberProviderForLV()
    {
        return [
            ['40003009497', true],
            ['40013009497', false],
            ['40003009496', false],
            ['1234567890', false],
            ['00212345678', false],
        ];
    }

    //ValidatorMT

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorMT
     * @dataProvider vatNumberProviderForMT
     * @param string $vatNumber
     */
    public function testValidatorMT(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('MT', $vatNumber, $state);
    }

    public function vatNumberProviderForMT()
    {
        return [
            ['15121333', true],
            ['15121332', false],
            ['1234567', false],
            ['05121333', false],
        ];
    }

    //ValidatorNL

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorNL
     * @dataProvider vatNumberProviderForNL
     * @param string $vatNumber
     */
    public function testValidatorNL(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('NL', $vatNumber, $state);
    }

    public function vatNumberProviderForNL()
    {
        return [
            ['010000446B01', true],
            ['010000436B01', false],
            ['12345678901', false],
            ['123456789A12', false],
            ['123456789B00', false],
        ];
    }

    //ValidatorPL

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorPL
     * @dataProvider vatNumberProviderForPL
     * @param string $vatNumber
     */
    public function testValidatorPL(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('PL', $vatNumber, $state);
    }

    public function vatNumberProviderForPL()
    {
        return [
            ['5260001246', true],
            ['12342678090', false],
            ['1212121212', false],
        ];
    }

    //ValidatorPT

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorPT
     * @dataProvider vatNumberProviderForPT
     * @param string $vatNumber
     */
    public function testValidatorPT(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('PT', $vatNumber, $state);
    }

    public function vatNumberProviderForPT()
    {
        return [
            ['502757191', true],
            ['502757192', false],
            ['12345678', false],
        ];
    }

    //ValidatorRO

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorRO
     * @dataProvider vatNumberProviderForRO
     * @param string $vatNumber
     */
    public function testValidatorRO(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('RO', $vatNumber, $state);
    }

    public function vatNumberProviderForRO()
    {
        return [
            ['11198699', true],
            ['14186770', true],
            ['11198698', false],
            ['1', false],
            ['12345678902', false],
        ];
    }

    //ValidatorSE

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorSE
     * @dataProvider vatNumberProviderForSE
     * @param string $vatNumber
     */
    public function testValidatorSE(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('SE', $vatNumber, $state);
    }

    public function vatNumberProviderForSE()
    {
        return [
            ['556188840401', true],
            ['556188840400', false],
            ['1234567890', false],
            ['556181140401', false],
        ];
    }

    //ValidatorSI

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorSI
     * @dataProvider vatNumberProviderForSI
     * @param string $vatNumber
     */
    public function testValidatorSI(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('SI', $vatNumber, $state);
    }

    public function vatNumberProviderForSI()
    {
        return [
            ['15012557', true],
            ['15012556', false],
            ['12345670', false],
            ['01234567', false],
            ['1234567', false],
        ];
    }

    //ValidatorSK

    /**
     * @covers \DragonBe\Vies\Validator\ValidatorSK
     * @dataProvider vatNumberProviderForSK
     * @param string $vatNumber
     */
    public function testValidatorSK(string $vatNumber, bool $state)
    {
        $this->validateVatNumber('SK', $vatNumber, $state);
    }

    public function vatNumberProviderForSK()
    {
        return [
            ['4030000007', true],
            ['4030000006', false],
            ['123456789', false],
            ['0123456789', false],
            ['4060000007', false],
        ];
    }
}
