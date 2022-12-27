<?php

namespace App\Test\TestCase\Action\API\Auth;

use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Application\Action\Api\Auth\RegisterAction
 */
class RegisterGuestActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testCreateUser(): void
    {
//        Chronos::setTestNow('2021-01-01 00:00:00'); TODO: Need?

        $request = $this->createJsonRequest(
            'POST',
            '/api/authentication/register/guest',
            [
                'visitorId' => 'randomstringasid'
            ]
        );

        $response = $this->app->handle($request);

        var_dump($response->getReasonPhrase());

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
        $this->assertTableRowCount(1, 'users');

        $expected = [
            'visitorId' => 'randomstringasid'
        ];

        $this->assertTableRow($expected, 'users', 1);
    }

    // TODO: Implement
//    public function testCreateUserValidation(): void
//    {
//        $request = $this->createJsonRequest(
//            'POST',
//            '/api/authentication/register/guest',
//            [
//                'visitorId' => ''
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
