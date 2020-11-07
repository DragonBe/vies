<?php

declare (strict_types=1);

/**
 * \DragonBe\Vies
 *
 * Component using the European Commission (EC) VAT Information Exchange System (VIES) to verify and validate VAT
 * registration numbers in the EU, using PHP and Composer.
 *
 * @author Michelangelo van Dam <dragonbe+github@gmail.com>
 * @license MIT
 *
 */
namespace DragonBe\Vies;

use DomainException;

/**
 * Class HeartBeat
 *
 * This class provides a simple but essential heartbeat cheack
 * on the VIES service as it's known to have availability issues.
 *
 * @category DragonBe
 * @package \DragonBe\Vies
 * @link http://ec.europa.eu/taxation_customs/vies/faqvies.do#item16
 */
class HeartBeat
{
    /**
     * @var string The host you want to verify
     */
    protected $host;
    /**
     * @var int The port you want to verify
     */
    protected $port;

    /**
     * @var int The timeout in seconds
     */
    protected $timeout;

    /**
     * @var bool Allow the service to be tested without integration of sockets
     */
    public static $testingEnabled = false;

    /**
     * @var bool Allow to define the validation return setting
     */
    public static $testingServiceIsUp = true;

    /**
     * @param string|null $host
     * @param int $port
     * @param int $timeout
     */
    public function __construct(?string $host = null, int $port = 80, int $timeout = 10)
    {
        if (null !== $host) {
            $this->setHost($host);
        }

        $this->setPort($port);
        $this->setTimeout($timeout);
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        if (null !== $this->host) {
            return $this->host;
        }

        throw new DomainException('A host is required');
    }

    /**
     * @param string $host
     * @return self
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return self
     */
    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return HeartBeat
     */
    public function setTimeout(int $timeout): HeartBeat
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Checks if the VIES service is online and available
     *
     * @return bool
     */
    public function isAlive(): bool
    {
        if (false === static::$testingEnabled) {
            return $this->reachOut();
        }

        return static::$testingServiceIsUp;
    }

    /**
     * A private routine to send a request over a socket to
     * test if the remote service is responding with a status
     * code of 200 OK. Now supports also proxy connections.
     *
     * @return bool
     */
    private function reachOut(): bool
    {
        $errno = 0;
        $error = '';
        $hostname = $this->getHost();
        $portNumber = $this->getPort();
        $timeToLive = $this->getTimeout();
        try {
            if (false === ($handle = \fsockopen('tcp://' . $hostname, $portNumber, $errno, $error, $timeToLive))) {
                return false;
            }
        } catch (\Exception $exception) {
            return false;
        }
        $response = '';
        $uri = sprintf('%s://%s/', Vies::VIES_PROTO, Vies::VIES_DOMAIN);
        $stream = [
            'GET ' . $uri . ' HTTP/1.0',
            'Host: ' . Vies::VIES_DOMAIN,
            'Connection: close',
        ];
        fwrite($handle, implode("\r\n", $stream) . "\r\n\r\n");
        while (! feof($handle)) {
            $response .= fgets($handle, 1024);
        }
        fclose($handle);
        $response = str_replace("\r\n", PHP_EOL, $response);
        $data = explode(PHP_EOL, $response);
        return (
            (0 === strcmp('HTTP/1.1 200 OK', $data[0])) ||
            (0 === strcmp('HTTP/1.1 307 Temporary Redirect', $data[0]))
        );
    }
}
