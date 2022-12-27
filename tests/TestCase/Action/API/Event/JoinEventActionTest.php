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
 * @coversDefaultClass \App\Application\Action\Api\Event\JoinEventAction
 */
class JoinEventActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testJoinEvent(): void
    {
//        Chronos::setTestNow('2021-01-01 00:00:00'); TODO: Need?

        $this->insertFixtures([UserFixture::class, EventFixture::class]);

        // TODO: Own Method?
        $secret = $this->container->get('settings')['authentication']['secret'];

        $payload = [
            'userId' => 1
        ];
        $jwt = JWT::encode($payload, $secret, 'HS256');
        // /TODO: Own Method?

        $request = $this->createJsonRequestWithHeaders(
            'GET',
            '/api/events/join/b1533600-f4e3-4c96-a8ab-2c496de62522',
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
        $this->assertJsonValue(true, 'success', $response);

        // Check logger
//        $this->assertTrue($this->getLogger()->hasInfoThatContains('User registered successfully'));

        // Check database
        $this->assertTableRowCount(1, 'users_events');

//        $expected = [
//            'title' => 'Updated Test Event',
//            'description' => 'Test Event',
//        ];

//        $this->assertTableRow($expected, 'events', 1);

        // TODO: More checks?
    }

    // TODO: Implement
//    public function testCreateUserValidation(): void
//    {
//        $request = $this->createJsonRequest(
//            'POST',
//            '/api/authentication/register',
//            [
//                'username' => '',
//                'password' => ''
//            ]
//        );
//
//        $response = $this->app->handle($request);
//
//        // Check response
//        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
//        $this->assertJsonContentType($response);
//
//        $expected = [
//            'error' => [
//                "message" => "Please check your input",
//                "details" => [
//                    [
//                        "message" => "This value should not be blank.",
//                        "field" => "[username]"
//                    ],
//                    [
//                        "message" => "This value is too short. It should have 3 characters or more.",
//                        "field" => "[username]"
//                    ],
//                    [
//                        "message" => "This value should not be blank.",
//                        "field" => "[password]"
//                    ],
//                    [
//                        "message" => "This value is too short. It should have 8 characters or more.",
//                        "field" => "[password]"
//                    ]
//                ]
//            ],
//        ];
//
//        $this->assertJsonData($expected, $response);
//    }

}
