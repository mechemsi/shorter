<?php

/**
 * Url shortener service
 *
 * @author Mechemsi
 */

declare(strict_types=1);

namespace App\Tests\Api;

use App\Tests\ApiTester;

class GenerateShortUrlCest
{
    public function _before(ApiTester $tester): void
    {
        $tester->haveHttpHeader('X-AUTH-TOKEN', 'test_token');
    }

    public function generateShortUrl(ApiTester $tester): void
    {
        $tester->sendPostAsJson('/generate', [
            'longUrl' => 'https://somthing.com',
        ]);
        $tester->seeResponseCodeIs(200);
        $tester->seeResponseIsJson();
        $tester->seeResponseContainsJson(['success' => true]);
        $tester->seeResponseMatchesJsonType([
            'success' => 'boolean',
            'shortUrl' => 'string',
        ]);
        $tester->clearEntityManager();
    }

    public function generateShortUrlExists(ApiTester $tester): void
    {
        $tester->sendPostAsJson('/generate', [
            'longUrl' => 'https://somthing.com',
        ]);
        $tester->seeResponseCodeIs(200);
        $tester->seeResponseIsJson();
        $tester->seeResponseContainsJson(['success' => true]);
        $tester->seeResponseMatchesJsonType([
            'success' => 'boolean',
            'shortUrl' => 'string',
        ]);
        $tester->sendPostAsJson('/generate', [
            'longUrl' => 'https://somthing.com',
        ]);
        $tester->seeResponseCodeIs(200);
        $tester->seeResponseIsJson();
        $tester->seeResponseContainsJson(['success' => true]);
        $tester->seeResponseMatchesJsonType([
            'success' => 'boolean',
            'shortUrl' => 'string',
        ]);
        $tester->clearEntityManager();
    }

    public function generateShortUrlError(ApiTester $tester): void
    {
        $tester->sendPostAsJson('/generate', [
            'longUrlas' => 'https://somthing.com',
        ]);
        $tester->seeResponseCodeIs(400);
        $tester->seeResponseIsJson();
        $tester->seeResponseContainsJson([
            'message' => 'Invalid form provided',
        ]);
    }

    public function generateShortUrlValidation(ApiTester $tester): void
    {
        $tester->sendPostAsJson('/generate', [
            'longUrl' => '',
        ]);
        $tester->seeResponseCodeIs(400);
        $tester->seeResponseIsJson();
        $tester->seeResponseContainsJson([
            'message' => 'Invalid form validation',
        ]);
    }
}
