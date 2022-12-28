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
 * @coversDefaultClass \App\Application\Action\Api\Necessity\CreateNecessityAction
 */
class AddNecessityActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testAddNecessity(): void
    {
//        Chronos::setTestNow('2021-01-01 00:00:00'); TODO: Need?

        $secret = $this->container->get('settings')['authentication']['secret'];

        $this->insertFixtures([UserFixture::class, EventFixture::class]);

        $payload = [
            'userId' => 1
        ];
        $jwt = JWT::encode($payload, $secret, 'HS256');

        $request = $this->createJsonRequestWithHeaders(
            'POST',
            '/api/necessity/add',
            [
                'name' => 'Necessity 1',
                'amount' => 2,
                'eventId' => 1
            ],
            'Bearer '.$jwt
        );

        $response = $this->app->handle($request);

        // No logger errors
        $this->assertSame([], $this->getLoggerErrors());
//        $this->assertTrue($this->getLogger()->hasInfoThatContains('User registered successfully'));

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_CREATED, $response->getStatusCode());
        $this->assertJsonContentType($response);
//        $this->assertJsonData(['customer_id' => 1], $response);
        $this->assertJsonValue(true, 'success', $response);

        // Check logger
//        $this->assertTrue($this->getLogger()->hasInfoThatContains('User registered successfully'));

        // Check database
        $this->assertTableRowCount(1, 'necessities');

        $expected = [
            'name' => 'Necessity 1',
            'amount' => 2,
            'event_id' => 1
        ];

        $this->assertTableRow($expected, 'necessities', 1);

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
