<?php

namespace App\Test\TestCase\Action\API\Event;

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
 * @coversDefaultClass \App\Application\Action\Api\Event\GetAllEventsAction
 */
class GetAllEventsActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testGetAllEvents(): void
    {
//        Chronos::setTestNow('2021-01-01 00:00:00'); TODO: Need?

        $this->insertFixtures([UserFixture::class]);
        $this->insertFixtures([EventFixture::class]);

        // TODO: Own Method?
        $secret = $this->container->get('settings')['authentication']['secret'];

        $payload = [
            'userId' => 1
        ];
        $jwt = JWT::encode($payload, $secret, 'HS256');
        // /TODO: Own Method?

        $request = $this->createJsonRequestWithHeaders(
            'GET',
            '/api/events/all',
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
            0 => [
                'id' => 1,
                'identifier' => '2def32d0-240f-4793-a397-074d2b82692c',
                'title' => 'Test Event',
                'description' => 'Test Event Description',
                'startDate' => [
                    'date' => '2022-12-27 00:00:00.000000',
                    'timezone_type' => 3,
                    'timezone' => 'Europe/Amsterdam'
                ],
                'endDate' => [
                    'date' => '2022-12-27 00:00:00.000000',
                    'timezone_type' => 3,
                    'timezone' => 'Europe/Amsterdam'
                ]
            ]
        ], $response);

        // Check logger
//        $this->assertTrue($this->getLogger()->hasInfoThatContains('User registered successfully'));

        // Check database
//        $this->assertTableRowCount(1, 'users');

//        $expected = [
//            'username' => 'Pieter2',
//            'visitorId' => null
//        ];

//        $this->assertTableRow($expected, 'users', 1);
    }
}
