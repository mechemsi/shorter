<?php

/**
 * Url shortener service
 *
 * @author Mechemsi
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use Symfony\Component\Form\FormInterface;

class UrlShortener
{
    private ShortUrlRepository $shortUrlRepository;
    private Generator $generator;

    public function __construct(
        ShortUrlRepository $shortUrlRepository,
        Generator $generator
    ) {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->generator = $generator;
    }

    public function generateShortUrl(FormInterface $form): ShortUrl
    {
        /** @var ShortUrl $short */
        $short = $form->getData();

        $exist = $this->shortUrlRepository->findOneBy([
            'longUrl' => $short->getLongUrl(),
        ]);

        if (null !== $exist) {
            return $exist;
        }

        $short->setShortUrl($this->shortenUrl());

        $this->shortUrlRepository->add($short, true);

        return $short;
    }

    public function getShortUrl(string $shortUrl): ?ShortUrl
    {
        $short = $this->shortUrlRepository->findOneBy([
            'shortUrl' => $shortUrl,
        ]);

        if (null !== $short) {
            $increase = intval($short->getClicks()) + 1;
            $short->setClicks($increase);
            $this->shortUrlRepository->add($short, true);
        }

        return $short;
    }

    private function shortenUrl(): string
    {
        $short = $this->generator->generate();
        if (null === $this->shortUrlRepository->findOneBy(['shortUrl' => $short])) {
            return $short;
        }

        return $this->shortenUrl();
    }
}
