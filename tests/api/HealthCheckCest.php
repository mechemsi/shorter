<?php

/**
 * Url shortener service
 *
 * @author Mechemsi
 */

declare(strict_types=1);

namespace App\Tests\Api;

use App\Tests\ApiTester;

class HealthCheckCest
{
    public function healthCheck(ApiTester $tester): void
    {
        $tester->haveHttpHeader('X-AUTH-TOKEN', 'test_token');
        $tester->sendGet('/health');
        $tester->seeResponseCodeIs(200);
        $tester->seeResponseIsJson();
        $tester->seeResponseContainsJson(['success' => true]);
    }

    public function healthCheckErrorAuth(ApiTester $tester): void
    {
        $tester->haveHttpHeader('X-AUTH-TOKEN', 'dev_token');
        $tester->sendGet('/health');
        $tester->seeResponseCodeIs(401);
    }

    public function healthCheckNoToken(ApiTester $tester): void
    {
        $tester->sendGet('/health');
        $tester->seeResponseCodeIs(401);
    }
}
