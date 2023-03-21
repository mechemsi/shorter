<?php

/**
 * Url shortener service
 *
 * @author Mechemsi
 */

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use App\Service\Generator;
use App\Service\UrlShortener;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\FormInterface;

class UrlShortenerTest extends Unit
{
    /** @var MockObject|ShortUrlRepository */
    private $shortUrlRepository;

    /** @var MockObject|Generator */
    private $generator;

    private UrlShortener $urlShortener;

    public function _before(): void
    {
        $this->shortUrlRepository = $this->createMock(ShortUrlRepository::class);
        $this->generator = $this->createMock(Generator::class);

        $this->urlShortener = new UrlShortener(
            $this->shortUrlRepository,
            $this->generator
        );
    }

    public function testGenerateShortUrlExists(): void
    {
        $longUrl = 'longurl';
        $short = $this->createMock(ShortUrl::class);
        $form = $this->createMock(FormInterface::class);

        $short->method('getLongUrl')->willReturn($longUrl);

        $form->expects(self::once())
            ->method('getData')
            ->willReturn($short);

        $this->shortUrlRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['longUrl' => $longUrl])
            ->willReturn($short);

        $result = $this->urlShortener->generateShortUrl($form);

        self::assertEquals($result, $short);
    }

    public function testGenerateShortUrl(): void
    {
        $longUrl = 'longurl';
        $shortUrl = 'shortUrl';
        $short = $this->createMock(ShortUrl::class);
        $form = $this->createMock(FormInterface::class);

        $short->method('getLongUrl')->willReturn($longUrl);
        $short->method('setShortUrl');

        $form->expects(self::once())
            ->method('getData')
            ->willReturn($short);

        $this->shortUrlRepository->expects(self::exactly(2))
            ->method('findOneBy')
            ->willReturnOnConsecutiveCalls(null, null);

        $this->shortUrlRepository->expects(self::once())
            ->method('add')
            ->with($short, true);

        $this->generator->expects(self::once())
            ->method('generate')
            ->willReturn($shortUrl);

        $result = $this->urlShortener->generateShortUrl($form);

        self::assertEquals($result, $short);
    }

    public function testGenerateShortUrlExistsSameShort(): void
    {
        $longUrl = 'longurl';
        $shortUrl = 'shortUrl';
        $shortUrl2 = 'shortUrl2';
        $short = $this->createMock(ShortUrl::class);
        $form = $this->createMock(FormInterface::class);

        $short->method('getLongUrl')->willReturn($longUrl);
        $short->method('setShortUrl');

        $form->expects(self::once())
            ->method('getData')
            ->willReturn($short);

        $this->shortUrlRepository->expects(self::exactly(3))
            ->method('findOneBy')
            ->willReturnOnConsecutiveCalls(null, $short, null);

        $this->shortUrlRepository->expects(self::once())
            ->method('add')
            ->with($short);

        $this->generator->expects(self::exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls($shortUrl, $shortUrl2);

        $result = $this->urlShortener->generateShortUrl($form);

        self::assertEquals($result, $short);
    }

    public function testGetShortUrl(): void
    {
        $shortUrl = 'shortUrl';
        $short = $this->createMock(ShortUrl::class);

        $short->method('getClicks')->willReturn(1);
        $short->expects(self::once())
            ->method('setClicks')
            ->with(2);

        $this->shortUrlRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['shortUrl' => $shortUrl])
            ->willReturn($short);

        $this->shortUrlRepository->expects(self::once())
            ->method('add')
            ->with($short, true);

        $result = $this->urlShortener->getShortUrl($shortUrl);

        self::assertEquals($result, $short);
    }

    public function testGetShortUrlNull(): void
    {
        $shortUrl = 'shortUrl';
        $short = $this->createMock(ShortUrl::class);

        $short->expects(self::never())
            ->method('setClicks');

        $this->shortUrlRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['shortUrl' => $shortUrl])
            ->willReturn(null);

        $this->shortUrlRepository->expects(self::never())
            ->method('add');

        $result = $this->urlShortener->getShortUrl($shortUrl);

        self::assertEquals($result, null);
    }
}
