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
    public const DEFAULT_TIMEOUT = 10;

    /**
     * @var string The host you want to verify
     */
    protected $host;
    /**
     * @var int The port you want to verify
     */
    protected $port;
    /**
     * @var ?string The path to append
     */
    protected $path;

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
    public function __construct(
        ?string $host = null,
        int $port = Vies::VIES_PORT,
        int $timeout = self::DEFAULT_TIMEOUT,
        ?string $path = null
    ) {
        if (null !== $host) {
            $this->setHost($host);
        }

        $this->setPath($path);
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
     * @return ?string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param ?string $path
     * @return self
     */
    public function setPath(?string $path = null): self
    {
        $this->path = $path;

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
        try {
            $data = $this->getSecuredResponse();
        } catch (\RuntimeException $runtimeException) {
            return false;
        }
        return (
            (0 === strcmp('HTTP/1.1 200 OK', $data[0])) ||
            (0 === strcmp('HTTP/1.1 307 Temporary Redirect', $data[0]))
        );
    }

    /**
     * This method will make a simple request inside a stream
     * resource to retrieve its contents. Useful inside secured
     * streams.
     *
     * @param resource $handle
     * @return array
     */
    private function readContents($handle): array
    {
        if (! is_resource($handle)) {
            throw new \InvalidArgumentException('Expecting a resource to be provided');
        }
        $response = '';
        $uri = sprintf('%s://%s%s', Vies::VIES_PROTO, $this->host, $this->path);
        $stream = [
            'GET ' . $uri . ' HTTP/1.0',
            'Host: ' . $this->host,
            'Connection: close',
        ];
        fwrite($handle, implode("\r\n", $stream) . "\r\n\r\n");
        while (! feof($handle)) {
            $response .= fgets($handle, 1024);
        }
        fclose($handle);
        $response = str_replace("\r\n", PHP_EOL, $response);
        $data = explode(PHP_EOL, $response);
        return $data;
    }

    /**
     * Will make a secured request over SSL/TLS where this
     * method will first create a secured stream before
     * making the request.
     *
     * @return array
     * @throws \RuntimeException
     * @see https://bytephunk.wordpress.com/2017/11/27/ssl-tls-stream-sockets-in-php-7/
     */
    private function getSecuredResponse(): array
    {
        $streamOptions = [
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => false,
            ],
        ];
        $streamContext = stream_context_create($streamOptions);
        $socketAddress = sprintf(
            'tls://%s:%d',
            $this->host,
            $this->port
        );
        $error = null;
        $errno = null;
        $stream = stream_socket_client(
            $socketAddress,
            $errno,
            $error,
            self::DEFAULT_TIMEOUT,
            STREAM_CLIENT_CONNECT,
            $streamContext
        );

        if (! $stream) {
            throw new \RuntimeException('Can not create socket stream: ' . $error);
        }

        return $this->readContents($stream);
    }
}
