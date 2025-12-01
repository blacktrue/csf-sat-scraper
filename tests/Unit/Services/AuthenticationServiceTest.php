<?php

declare(strict_types=1);

namespace Blacktrue\CsfSatScraper\Tests\Unit\Services;

use Blacktrue\CsfSatScraper\Exceptions\InvalidCaptchaException;
use Blacktrue\CsfSatScraper\Exceptions\InvalidCredentialsException;
use Blacktrue\CsfSatScraper\Exceptions\NetworkException;
use Blacktrue\CsfSatScraper\Services\AuthenticationService;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\RequestInterface;
use PHPUnit\Framework\TestCase;

class AuthenticationServiceTest extends TestCase
{
    private ClientInterface $mockClient;
    private string $validRfc = 'XAXX010101000';
    private string $validPassword = 'testPassword123';

    protected function setUp(): void
    {
        $this->mockClient = $this->createMock(ClientInterface::class);
    }

    public function testInitializeLoginSuccess(): void
    {
        $this->mockClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/nidp/app/login');

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);
        $service->initializeLogin();

        $this->assertTrue(true);
    }

    public function testGetLoginFormReturnsHtml(): void
    {
        $expectedHtml = '<form><div id="divCaptcha"><img src="captcha.jpg" /></div></form>';

        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('__toString')->willReturn($expectedHtml);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $this->mockClient
            ->method('request')
            ->willReturn($mockResponse);

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);
        $result = $service->getLoginForm();

        $this->assertSame($expectedHtml, $result);
    }

    public function testSendLoginFormSuccess(): void
    {
        $expectedResponse = '<html>Login submitted</html>';

        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('__toString')->willReturn($expectedResponse);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $this->mockClient
            ->method('request')
            ->willReturn($mockResponse);

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);
        $result = $service->sendLoginForm('ABC123');

        $this->assertSame($expectedResponse, $result);
    }

    public function testCheckLoginSucceeds(): void
    {
        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('__toString')->willReturn('Welcome ' . $this->validRfc);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $this->mockClient
            ->method('request')
            ->willReturn($mockResponse);

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);
        $service->checkLogin();

        $this->assertTrue(true);
    }

    public function testInitializeLoginThrowsNetworkException(): void
    {
        $mockRequest = $this->createMock(RequestInterface::class);
        $exception = new ConnectException('Connection timeout', $mockRequest);

        $this->mockClient
            ->method('request')
            ->willThrowException($exception);

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Failed to initialize login session');

        $service->initializeLogin();
    }

    public function testGetLoginFormThrowsNetworkException(): void
    {
        $mockRequest = $this->createMock(RequestInterface::class);
        $exception = new ConnectException('Connection failed', $mockRequest);

        $this->mockClient
            ->method('request')
            ->willThrowException($exception);

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Failed to get login form');

        $service->getLoginForm();
    }

    public function testSendLoginFormThrowsNetworkException(): void
    {
        $mockRequest = $this->createMock(RequestInterface::class);
        $exception = new ConnectException('Network unreachable', $mockRequest);

        $this->mockClient
            ->method('request')
            ->willThrowException($exception);

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Failed to send login form');

        $service->sendLoginForm('ABC123');
    }

    public function testCheckLoginThrowsNetworkException(): void
    {
        $mockRequest = $this->createMock(RequestInterface::class);
        $exception = new ConnectException('Connection failed', $mockRequest);

        $this->mockClient
            ->method('request')
            ->willThrowException($exception);

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Failed to check login');

        $service->checkLogin();
    }

    public function testCheckLoginThrowsInvalidCaptchaException(): void
    {
        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('__toString')->willReturn('<html>El captcha es incorrecto</html>');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $this->mockClient
            ->method('request')
            ->willReturn($mockResponse);

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);

        $this->expectException(InvalidCaptchaException::class);
        $this->expectExceptionMessage('Invalid captcha');

        $service->checkLogin();
    }

    public function testCheckLoginThrowsInvalidCredentialsException(): void
    {
        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('__toString')->willReturn('<html>Login failed</html>');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $this->mockClient
            ->method('request')
            ->willReturn($mockResponse);

        $service = new AuthenticationService($this->mockClient, $this->validRfc, $this->validPassword);

        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage('Invalid credentials');

        $service->checkLogin();
    }
}

