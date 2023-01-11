<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use DomainException;
use DragonBe\Vies\HeartBeat;
use DragonBe\Vies\Vies;
use PHPUnit\Framework\TestCase;

/**
 * Class HeartBeatTest
 *
 * @package DragonBe\Test\Vies
 * @coversDefaultClass \DragonBe\Vies\HeartBeat
 */
class HeartBeatTest extends TestCase
{
    /**
     * @covers ::getHost
     */
    public function testExceptionThrownWhenNoHostIsConfigured()
    {
        $this->expectException(DomainException::class);
        (new HeartBeat())->getHost();
    }

    /**
     * @covers ::getPort
     */
    public function testDefaultPortIsHttp()
    {
        $hb = new HeartBeat();
        $port = $hb->getPort();
        $this->assertSame(443, $port);
    }

    /**
     * @covers ::setHost
     * @covers ::getHost
     */
    public function testCanSetHost()
    {
        $host = 'www.example.com';
        $hb = new HeartBeat();
        $hb->setHost($host);
        $this->assertSame($host, $hb->getHost());
    }

    /**
     * @covers ::setPort
     * @covers ::getPort
     */
    public function testCanSetPort()
    {
        $port = 443;
        $hb = new HeartBeat();
        $hb->setPort($port);
        $this->assertSame($port, $hb->getPort());
    }

    /**
     * @covers ::setTimeout
     * @covers ::getTimeout
     */
    public function testCanSetTimeout()
    {
        $timeout = 300;
        $hb = new HeartBeat();
        $hb->setTimeout($timeout);
        $this->assertSame($timeout, $hb->getTimeout());
    }

    /**
     * @covers ::__construct
     */
    public function testCanOverrideSettingsAtConstruct()
    {
        $host = 'www.example.com';
        $port = 8080;
        $hb = new HeartBeat($host, $port);
        $this->assertSame($host, $hb->getHost());
        $this->assertSame($port, $hb->getPort());
    }

    /**
     * @covers ::isAlive
     */
    public function testVerifyServicesIsAlive()
    {
        $host = '127.0.0.1';
        $port = -1;
        HeartBeat::$testingEnabled = true;
        HeartBeat::$testingServiceIsUp = true;
        $hb = new HeartBeat($host, $port);
        $this->assertTrue($hb->isAlive());
    }

    /**
     * @covers ::isAlive
     */
    public function testVerifyServicesIsDown()
    {
        $host = '127.0.0.1';
        $port = -1;
        HeartBeat::$testingEnabled = true;
        HeartBeat::$testingServiceIsUp = false;
        $hb = new HeartBeat($host, $port);
        $this->assertFalse($hb->isAlive());
    }

    public function socketProvider(): array
    {
        return [
            'Non-existing socket on localhost' => ['127.0.0.1', -1, 10, null, false],
            'Socket 443 on ec.europe.eu' => [Vies::VIES_DOMAIN, Vies::VIES_PORT, 10, null, false],
            'Socket 443 on ec.europe.eu'.Vies::VIES_PATH => [Vies::VIES_DOMAIN, Vies::VIES_PORT, 10, Vies::VIES_PATH, true],
        ];
    }

    /**
     * @dataProvider socketProvider
     * @covers ::isAlive
     * @covers ::reachOut
     * @covers ::getSecuredResponse
     * @covers ::readContents
     */
    public function testIsAliveUsingSockets($host, $port, $timeout, $path, $expectedResult)
    {
        HeartBeat::$testingEnabled = false;
        $heartBeat = new HeartBeat($host, $port, $timeout, $path);
        $actualResult = $heartBeat->isAlive();
        $this->assertSame($expectedResult, $actualResult);
    }
}
