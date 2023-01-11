<?php

/**
 * Url shortener service
 *
 * @author Mechemsi
 */

declare(strict_types=1);

namespace App\Tests\Api;

use App\Tests\ApiTester;

class GenerateShortUrlCestNoToken
{
    public function generateShortUrlNoTokenError(ApiTester $tester): void
    {
        $tester->sendPostAsJson('/generate', [
            'longUrl' => 'https://somthing.com',
        ]);
        $tester->seeResponseCodeIs(401);
        $tester->seeResponseIsJson();
    }

    public function generateShortUrlBadTokenError(ApiTester $tester): void
    {
        $tester->haveHttpHeader('X-AUTH-TOKEN', 'dev_token');
        $tester->sendPostAsJson('/generate', [
            'longUrl' => 'https://somthing.com',
        ]);
        $tester->seeResponseCodeIs(401);
    }
}
