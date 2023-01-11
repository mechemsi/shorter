<?php

/**
 * Url shortener service
 *
 * @author Mechemsi
 */

declare(strict_types=1);

namespace App\Service;

class Generator
{
    public const SHORT_URL_LENGTH = 7;
    public const RANDOM_BYTES = 32;

    public function generate(): string
    {
        return substr(
            base64_encode(
                sha1(
                    uniqid(
                        random_bytes(self::RANDOM_BYTES),
                        true
                    )
                )
            ),
            0,
            self::SHORT_URL_LENGTH
        );
    }
}
