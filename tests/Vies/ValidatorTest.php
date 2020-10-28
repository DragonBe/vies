<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesServiceException;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function vatNumberProvider()
    {
        return [
            'AT' => ['U10223006', ['U1022300', 'A10223006', 'U10223005']],
            'BE' => ['0776091951', ['0776091952', '07760919']],
            'BG' => [['204514061', '301004503'], ['10100450', '301004502']],
            'CY' => ['00532445O', ['005324451', '0053244511', '12000139V', '72000139V']],
            'CZ' => [
                ['46505334', '7103192745', '640903926', '395601439', '630903928', '27082440'],
                [
                    '4650533', '96505334', '46505333', '7103192743', '1903192745', '7133192745',
                    '395632439', '396301439', '545601439', '640903927', '7103322745'
                ]
            ],
            'DE' => ['111111125', ['111111124', '1234567']],
            'DK' => ['88146328', ['88146327', '1234567']],
            'EE' => ['100207415', ['1002074', 'A12345678']],
            'EL' => ['040127797', ['040127796', '1234567']],
            'ES' => [['A0011012B', 'A78304516'], ['K0011012B', '12345678', 'K001A012B', 'A0011012C']],
            'FI' => ['09853608', ['09853607', '1234567']],
            'FR' => [
                ['00300076965', 'K7399859412', '4Z123456782'],
                ['0030007696A', '1234567890', 'K6399859412', 'KO399859412', 'IO399859412']
            ],
            'GB' => [['434031494', 'GD001', 'HA500'], ['434031493', '12345', 'GD500', 'HA100', '12345678']],
            'HR' => ['38192148118', ['3819214811', '1234567890A']],
            'HU' => [['21376414', '10597190'], ['2137641', '1234567A']],
            'IE' => [['8Z49289F', '3628739L', '5343381W', '6433435OA'], ['8Z49389F', '1234567', '6433435OB']],
            'IT' => ['00000010215', ['00000010214', '1234567890', '00000001234']],
            'LT' => [
                ['210061371310', '213179412', '290061371314', '208640716'],
                ['213179422', '21317941', '1234567890', '1234567890AB']
            ],
            'LU' => ['10000356', ['10000355', '1234567']],
            'LV' => ['40003009497', ['40013009497', '40003009496', '1234567890', '00212345678']],
            'MT' => ['15121333', ['15121332', '1234567', '05121333']],
            'NL' => [
                ['010000446B01', '000099998B57'],
                ['010000436B01', '12345678901', '123456789A12', '123456789B00', '0$0099998B57']],
            'PL' => ['5260001246', ['12342678090', '1212121212']],
            'PT' => ['502757191', ['502757192', '12345678']],
            'RO' => [['11198699', '14186770'], ['11198698', '1', '12345678902']],
            'SE' => ['556188840401', ['556188840400', '1234567890', '556181140401']],
            'SI' => ['15012557', ['15012556', '12345670', '01234567', '1234567']],
            'SK' => ['4030000007', ['4030000006', '123456789', '0123456789', '4060000007']]
        ];
    }

    public function testVatNumberChecksumSuccess()
    {
        $vies = new Vies();

        foreach ($this->vatNumberProvider() as $country => $numbers) {
            if (! is_array($numbers[0])) {
                $numbers[0] = [$numbers[0]];
            }
            foreach ($numbers[0] as $number) {
                $result = $vies->validateVatSum($country, $number);
                $this->assertTrue(
                    $result,
                    'VAT ID ' . $country . $number . ' should validate, but is not valid'
                );
            }
        }
    }

    public function testVatNumberChecksumFailure()
    {
        $vies = new Vies();

        foreach ($this->vatNumberProvider() as $country => $numbers) {
            foreach ($numbers[1] as $number) {
                $result = $vies->validateVatSum($country, $number);
                $this->assertFalse($result);
            }
        }
    }

    public function traderDataProvider()
    {
        return [
            'Belgian Trader Name' => [
                [
                    'countryCode'          => 'BE',
                    'vatNumber'            => '0203430576',
                    'requesterCountryCode' => 'BE',
                    'requesterVatNumber'   => '0203430576',
                    'traderName'           => 'B-Rail',
                    'traderCompanyType'    => 'NV',
                    'traderStreet'         => 'Frankrijkstraat 65',
                    'traderPostcode'       => '1060',
                    'traderCity'           => 'Sint-Gillis',
                ],
            ],
            'German Trader Name' => [
                [
                    'countryCode'          => 'DE',
                    'vatNumber'            => '811569869',
                    'requesterCountryCode' => 'DE',
                    'requesterVatNumber'   => '811569869',
                    'traderName'           => 'Deutsche Bahn',
                    'traderCompanyType'    => 'AG',
                    'traderStreet'         => 'Potsdamer Platz 2',
                    'traderPostcode'       => '10785',
                    'traderCity'           => 'Berlin',
                ],
            ],
            'Greek Trader Name' => [
                [
                    'countryCode'          => 'EL',
                    'vatNumber'            => '999645865',
                    'requesterCountryCode' => 'EL',
                    'requesterVatNumber'   => '999645865',
                    'traderName'           => 'ΤΡΑΙΝΟΣΕ',
                    'traderCompanyType'    => 'AE',
                    'traderStreet'         => 'ΚΑΡΟΛΟΥ 1-3',
                    'traderPostcode'       => '10437',
                    'traderCity'           => 'ΑΘΗΝΑ',
                ],
            ],
            'Polish Trader Name' => [
                [
                    'countryCode'          => 'PL',
                    'vatNumber'            => '1132316427',
                    'requesterCountryCode' => 'PL',
                    'requesterVatNumber'   => '1132316427',
                    'traderName'           => 'PKP POLSKIE LINIE KOLEJOWE SPÓŁKA AKCYJNA',
                    'traderCompanyType'    => '',
                    'traderStreet'         => 'TARGOWA 74',
                    'traderPostcode'       => '03-734',
                    'traderCity'           => 'WARSZAWA',
                ],
            ],
            'Ampesant Trader Name' => [
                [
                    'countryCode'          => 'BE',
                    'vatNumber'            => '0458591947',
                    'requesterCountryCode' => 'BE',
                    'requesterVatNumber'   => '0458591947',
                    'traderName'           => 'VAN AERDE & PARTNERS',
                    'traderCompanyType'    => 'BVBA',
                    'traderStreet'         => 'RIJSELSTRAAT 274',
                    'traderPostcode'       => '8200',
                    'traderCity'           => 'BRUGGE',
                ],
            ],
            'Dot-dash Trader Name' => [
                [
                    'countryCode'          => 'BE',
                    'vatNumber'            => '0467609086',
                    'requesterCountryCode' => 'BE',
                    'requesterVatNumber'   => '0467609086',
                    'traderName'           => 'HAELTERMAN C.V.-KLIMA',
                    'traderCompanyType'    => 'BVBA',
                    'traderStreet'         => 'GERAARDSBERGSESTEENWEG 307',
                    'traderPostcode'       => '9404',
                    'traderCity'           => 'NINOVE',
                ],
            ],
            'Accent Trader Name' => [
                [
                    'countryCode'          => 'BE',
                    'vatNumber'            => '0873284862',
                    'requesterCountryCode' => 'BE',
                    'requesterVatNumber'   => '0873284862',
                    'traderName'           => '\'t GERIEF',
                    'traderCompanyType'    => 'CVBA',
                    'traderStreet'         => 'LICHTAARTSEWEG(HRT) 22',
                    'traderPostcode'       => '2200',
                    'traderCity'           => 'HERENTALS',
                ],
            ],
            'Plus Trader Name' => [
                [
                    'countryCode'          => 'BE',
                    'vatNumber'            => '0629758840',
                    'requesterCountryCode' => 'BE',
                    'requesterVatNumber'   => '0629758840',
                    'traderName'           => 'ARCHITECTUUR+',
                    'traderCompanyType'    => 'BVBA',
                    'traderStreet'         => 'STATIONSSTRAAT 28',
                    'traderPostcode'       => '3930',
                    'traderCity'           => 'HAMONT-ACHEL',
                ],
            ],
        ];
    }

    /**
     * Testing that arguments that contain non-latin values are still
     * validated correctly
     *
     * @group issue-99
     * @covers \DragonBe\Vies\Vies::validateVat
     * @covers \DragonBe\Vies\Vies::validateArgument
     * @dataProvider TraderDataProvider
     */
    public function testArgumentValidationSucceedsForNonLatinArgumentValues(array $traderData)
    {
        $vies = new Vies();
        try {
            $vatResponse = $vies->validateVat(
                $traderData['countryCode'],
                $traderData['vatNumber'],
                $traderData['requesterCountryCode'],
                $traderData['requesterVatNumber'],
                $traderData['traderName'],
                $traderData['traderCompanyType'],
                $traderData['traderStreet'],
                $traderData['traderPostcode'],
                $traderData['traderCity']
            );
            $this->assertTrue($vatResponse->isValid());
        } catch (ViesServiceException $viesServiceException) {
            $this->markTestSkipped('Service unavailable at the moment');
        }
    }
}
