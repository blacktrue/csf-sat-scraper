<?php

declare(strict_types=1);

namespace Blacktrue\CsfSatScraper\Tests\Unit;

use Blacktrue\CsfSatScraper\Scraper;
use GuzzleHttp\ClientInterface;
use PhpCfdi\ImageCaptchaResolver\CaptchaResolverInterface;
use PHPUnit\Framework\TestCase;

class ScraperTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $mockCaptchaResolver = $this->createMock(CaptchaResolverInterface::class);

        $scraper = new Scraper(
            $mockClient,
            $mockCaptchaResolver,
            'TEST_RFC',
            'TEST_PASSWORD'
        );

        $this->assertSame($mockClient, $scraper->getClient());
    }
}
