<?php

namespace App\Test\TestCase\Action\API\Necessity;

use App\Test\Fixture\EventFixture;
use App\Test\Fixture\UserFixture;
use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Application\Action\Api\Necessity\RemoveNecessityAction
 */
class RemoveNecessityActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testRemoveEvent(): void
    {
        // TODO: Implement
//        Chronos::setTestNow('2021-01-01 00:00:00'); TODO: Need?

        $this->insertFixtures([UserFixture::class, EventFixture::class]);
//        $this->insertFixtures([EventFixture::class]);

        // TODO: Own Method?
        $secret = $this->container->get('settings')['authentication']['secret'];

        $payload = [
            'userId' => 1
        ];
        $jwt = JWT::encode($payload, $secret, 'HS256');
        // /TODO: Own Method?

        $request = $this->createJsonRequestWithHeaders(
            'GET',
            '/api/events/remove/1',
            null,
            'Bearer '.$jwt
        );

        $response = $this->app->handle($request);

        // No logger errors
        $this->assertSame([], $this->getLoggerErrors());
//        $this->assertTrue($this->getLogger()->hasInfoThatContains('User registered successfully'));

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
//        $this->assertJsonData(['customer_id' => 1], $response);
        $this->assertJsonData([
            'success' => true,
        ], $response);

        // Check logger
//        $this->assertTrue($this->getLogger()->hasInfoThatContains('User registered successfully'));

        // Check database
        $this->assertTableRowCount(1, 'events');

//        $expected = [
//            'username' => 'Pieter2',
//            'visitorId' => null
//        ];

//        $this->assertTableRow($expected, 'users', 1);
    }
}
