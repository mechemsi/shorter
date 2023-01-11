<?php

/**
 * Url shortener service
 *
 * @author Mechemsi
 */

declare(strict_types=1);

namespace App\Tests\Api;

use App\Tests\ApiTester;

class GetShortUrlCest
{
    public function getShortUrl(ApiTester $tester): void
    {
        $tester->haveHttpHeader('X-AUTH-TOKEN', 'test_token');
        $tester->sendPostAsJson('/generate', [
            'longUrl' => 'https://google.com',
        ]);
        $tester->seeResponseCodeIs(200);
        $tester->seeResponseIsJson();
        $tester->seeResponseContainsJson(['success' => true]);
        $tester->seeResponseMatchesJsonType([
            'success' => 'boolean',
            'shortUrl' => 'string',
        ]);
        $response = (array) json_decode($tester->grabResponse(), true);

        $urls = explode('/', strval($response['shortUrl']));
        $tester->stopFollowingRedirects();
        $tester->sendGet('/' . end($urls));
        $tester->seeResponseCodeIs(301);
        $tester->seeHttpHeader('Location', 'https://google.com');

        $tester->clearEntityManager();
    }

    public function getShortUrlNotfound(ApiTester $tester): void
    {
        $tester->stopFollowingRedirects();
        $tester->sendGet('/test');
        $tester->seeResponseCodeIs(404);
    }

    public function indexRedirect(ApiTester $tester): void
    {
        $tester->stopFollowingRedirects();
        $tester->sendGet('/');
        $tester->seeResponseCodeIs(301);
    }
}
