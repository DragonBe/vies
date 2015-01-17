<?php
namespace DragonBe\Test\Vies;

use DragonBe\Vies\HeartBeat;

class HeartBeatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \DomainException
     * @covers \DragonBe\Vies\HeartBeat::getHost
     */
    public function testExceptionThrownWhenNoHostIsConfigured()
    {
        $hb = new HeartBeat();
        $host = $hb->getHost();
        $this->assertNull($host);
    }

    /**
     * @covers \DragonBe\Vies\HeartBeat::getPort
     */
    public function testDefaultPortIsHttp()
    {
        $hb = new HeartBeat();
        $port = $hb->getPort();
        $this->assertSame(80, $port);
    }

    /**
     * @covers DragonBe\Vies\HeartBeat::setHost
     * @covers DragonBe\Vies\HeartBeat::getHost
     */
    public function testCanSetHost()
    {
        $host = 'www.example.com';
        $hb = new HeartBeat();
        $hb->setHost($host);
        $this->assertSame($host, $hb->getHost());
    }

    /**
     * @covers DragonBe\Vies\HeartBeat::setPort
     */
    public function testCanSetPort()
    {
        $port = 443;
        $hb = new HeartBeat();
        $hb->setPort($port);
        $this->assertSame($port, $hb->getPort());
    }

    /**
     * @covers DragonBe\Vies\HeartBeat::__construct
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
     * @covers DragonBe\Vies\HeartBeat::isAlive
     * @covers DragonBe\Vies\HeartBeat::connect
     */
    public function testVerifyServicesIsAlive()
    {
        $host = '127.0.0.1';
        $port = -1;
        HeartBeat::$testingEnabled = true;
        HeartBeat::$testingServiceIsAlive = true;
        $hb = new HeartBeat($host, $port);
        $result = $hb->isAlive();
        $this->assertTrue($result);
    }

    /**
     * @covers DragonBe\Vies\HeartBeat::isAlive
     * @covers DragonBe\Vies\HeartBeat::connect
     */
    public function testVerifyServicesIsDown()
    {
        $host = '127.0.0.1';
        $port = -1;
        HeartBeat::$testingEnabled = true;
        HeartBeat::$testingServiceIsAlive = false;
        $hb = new HeartBeat($host, $port);
        $result = $hb->isAlive();
        $this->assertFalse($result);
    }
}
